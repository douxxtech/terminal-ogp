<?php
/*

By github.com/douxxtech
A DPIP.lol project.

-------------------------

Replace "YOUR_GITHUB_TOKEN" by a real github account token (needs the repos perms)

-------------------------

For help contact douxx@douxx.tech
*/
define('GIT_TKN', 'YOUR_GITHUB_TOKEN');

header('Content-Type: image/svg+xml');

//if we set the parameter "cache" to false, we prevent the websites (that are not a mess) to cache our responses
if (isset($_GET['cache']) && strtolower($_GET['cache']) === 'false') {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Expires: 0');
    header('Pragma: no-cache');
}

// fallback url if something goes wrong
$failUrl = null;

if (isset($_GET['failurl'])) {
    $candidate = $_GET['failurl'];

    if (filter_var($candidate, FILTER_VALIDATE_URL)) {
        $parsed = parse_url($candidate);

        if (isset($parsed['scheme']) && in_array($parsed['scheme'], ['http', 'https'], true)) {
            $failUrl = $candidate;
        }
    }
}

//to return error.svg if something failed
function sendErrorResponse($errorMessage) {
    global $failUrl;

    if ($failUrl !== null) {
        $separator = str_contains($failUrl, '?') ? '&' : '?';
        header('Location: ' . $failUrl . $separator . 'error=' . urlencode($errorMessage), true, 302);
        exit;
    }

    header('Content-Type: image/svg+xml');
    $errorSVG = file_get_contents('svg/error.svg');
    $errorSVG = str_replace('{{errorMessage}}', htmlspecialchars($errorMessage), $errorSVG);
    echo $errorSVG;
    exit;
}

//to fetch the github repository with a github token so we are less ratelimited
function fetchGitHubRepo($owner, $repo) {
    $url = "https://api.github.com/repos/$owner/$repo";
    $headers = [
        "Authorization: token " . GIT_TKN,
        "User-Agent: terminalGitOpenGraph"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        sendErrorResponse('Request failed: ' . curl_error($ch));
    }

    curl_close($ch);
    return json_decode($response, true);
}

function fetchExternalSVG(string $url): string {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        sendErrorResponse('Invalid SVG URL.');
    }

    $parsed = parse_url($url);
    if (!isset($parsed['scheme']) || $parsed['scheme'] !== 'https') {
        sendErrorResponse('Only HTTPS SVG URLs are allowed.');
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_USERAGENT => 'terminalGitOpenGraph',
        CURLOPT_MAXREDIRS => 3,
    ]);

    $svg = curl_exec($ch);

    if (curl_errno($ch)) {
        sendErrorResponse('Failed to fetch external SVG.');
    }

    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if (
        !$svg ||
        strpos($svg, '<svg') === false ||
        ($contentType && !str_contains($contentType, 'image/svg'))
    ) {
        sendErrorResponse('URL does not point to a valid SVG.');
    }

    if (strlen($svg) > 200_000) {
        sendErrorResponse('SVG file too large.');
    }

    $svg = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $svg); #prevent scripts

    return $svg;
}


//checks if the owner and repo parameters are here
if (!isset($_GET['owner']) || !isset($_GET['repo'])) {
    sendErrorResponse('Missing owner or repo parameter.');
}
$ownerRaw = $_GET['owner'];
$repoRaw  = $_GET['repo'];
$owner = htmlspecialchars($_GET['owner']);
$repo = htmlspecialchars($_GET['repo']);

$repoData = fetchGitHubRepo($ownerRaw, $repoRaw);

//checks if the response is okey
if (isset($repoData['message'])) {
    if ($repoData['message'] === 'Not Found') {
        sendErrorResponse('Repository not found.');
    } elseif ($repoData['message'] === 'API rate limit exceeded for') {
        sendErrorResponse('Rate limit exceeded. Try again later.');
    } else {
        sendErrorResponse($repoData['message']);
    }
}

//get the theme or use the theme bash-dark-status by default

if (isset($_GET['svg'])) {
    $svgContent = fetchExternalSVG($_GET['svg']);
} else {
    $theme = isset($_GET['theme']) ? htmlspecialchars($_GET['theme']) : 'bash-dark-stats';
    $themeFile = "svg/$theme.svg";

    if (!file_exists($themeFile)) {
        sendErrorResponse('Theme not found.');
    }

    $svgContent = file_get_contents($themeFile);
}


//To not show big numbers, format the numbers to k
function formatNumber($number) {
    return $number >= 1000 ? round($number / 1000, 1) . 'k' : $number;
}

//defines and replaces the svg variables
$repoNameFormatted = ucfirst($repo);

$stars = htmlspecialchars(formatNumber($repoData['stargazers_count']));
$contributors = htmlspecialchars(formatNumber($repoData['network_count']));
$issues = htmlspecialchars(formatNumber($repoData['open_issues_count']));
$forks = htmlspecialchars(formatNumber($repoData['forks_count']));
$language = htmlspecialchars($repoData['language'] ?? 'Unknown');
$defaultBranch = htmlspecialchars($repoData['default_branch'] ?? 'N/A');
$createdAt = htmlspecialchars(date('F j, Y', strtotime($repoData['created_at'])));
$updatedAt = htmlspecialchars(date('F j, Y', strtotime($repoData['updated_at'])));
$description = htmlspecialchars($repoData['description'] ?? 'No description provided');
$homepage = htmlspecialchars($repoData['homepage'] ?? 'No homepage');
$license = htmlspecialchars(isset($repoData['license']['name']) ? $repoData['license']['name'] : 'No license');
$ownerLogin = htmlspecialchars($repoData['owner']['login'] ?? 'Unknown');
$ownerAvatarUrl = htmlspecialchars($repoData['owner']['avatar_url'] ?? '');

$svgContent = str_replace(
    ['{{owner}}', '{{repo}}', '{{stars}}', '{{contributors}}', '{{issues}}', '{{forks}}', '{{language}}', '{{defaultBranch}}', '{{createdAt}}', '{{updatedAt}}', '{{description}}', '{{homepage}}', '{{license}}', '{{ownerLogin}}', '{{ownerAvatarUrl}}'],
    [$owner, $repo, $stars, $contributors, $issues, $forks, $language, $defaultBranch, $createdAt, $updatedAt, $description, $homepage, $license, $ownerLogin, $ownerAvatarUrl],
    $svgContent
);

//if tha avatar is set to true in the parameters, show the owner avatar on the bottom right corner
if (isset($_GET['avatar']) && strtolower($_GET['avatar']) === 'true') {
    //avatar styles, change them if you want
    $avatarSize = 150;
    $avatarX = 1100 - $avatarSize; //complex math
    $avatarY = 350;
    $cornerRadius = 20;
    
    $svgContent = str_replace(
        '</svg>',
        "<g transform=\"translate($avatarX, $avatarY)\">
            <rect width=\"$avatarSize\" height=\"$avatarSize\" rx=\"$cornerRadius\" ry=\"$cornerRadius\" fill=\"white\" opacity=\"0.1\" />
            <image href=\"$ownerAvatarUrl\" x=\"0\" y=\"0\" width=\"$avatarSize\" height=\"$avatarSize\" clip-path=\"url(#clip)\" />
        </g>
        <defs>
            <clipPath id=\"clip\">
                <rect width=\"$avatarSize\" height=\"$avatarSize\" rx=\"$cornerRadius\" ry=\"$cornerRadius\" />
            </clipPath>
        </defs>
        </svg>",
        $svgContent
    );
}

echo $svgContent;
?>
