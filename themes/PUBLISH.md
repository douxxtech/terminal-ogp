## Adding your own theme to this repository

To add your theme, you must follow these steps and rules:

1. Clone the repo localy
2. Create 6 svg files into src/svg: `<theme-name>-<dark/light>-<all/specs/desc>.svg`

Those svg will contain the returned image, to use elements returned by the github api, use those variables:
```json
[$owner, $repo, $stars, $contributors, $issues, $forks, $language, $defaultBranch, $createdAt, $updatedAt, $description, $homepage, $license, $ownerLogin, $ownerAvatarUrl]
```

3. Once your themes finished and tested, do a Pull Request on this repo reporting inside:
the name of a theme
an image for each svg of the theme

4. You will get a response of approval or denial fastly.