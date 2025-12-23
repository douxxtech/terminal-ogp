<div align="center">
  <a href="#" style="display: block; text-align: center;">
    <img 
      alt="Image of this repo" 
      src="https://togp.xyz?owner=douxxtech&repo=terminal-ogp&theme=json-dark-all&cache=false" 
      type="image/svg+xml" 
      style="border-radius: 20px; overflow: hidden;" 
    />
    <h1 align="center">Terminal-ogp</h1>
  </a>
</div>

<p align="center">
  Bored of github default opengraph ? Replace it !
</p>

<p align="center">
  <a href="#introduction"><strong>Introduction</strong></a> ·
  <a href="#features"><strong>Features</strong></a> ·
  <a href="#how-to-use"><strong>How to use</strong></a> ·
  <a href="#Themes"><strong>Themes</strong></a> ·
  <a href="#deployment"><strong>Deployment</strong></a> ·
  <a href="#faq"><strong>FAQ</strong></a>
</p>

<br/>

# Important Notices

> [!IMPORTANT]\
> Since the GitHub API only [allows 5k requests per hour per user account](https://docs.github.com/en/graphql/overview/resource-limitations), the public instance hosted on `https://togp.xyz` could possibly hit the rate limited and result in a ratelimit error response. To avoid this, prioritze using caching on github or [deploying your own instance](#deployment)

> [!IMPORTANT]\
> This is a small project, and the host is not free. To support me, consider starring this repo!


## Introduction

`Terminal ogp` is providing an alternative to [github opengraphs](https://opengraph.githubassets.com/somerandomshit/douxxtech/terminal-ogp) images that i find kinda boring. It uses images that are terminal-like to get a more "tecchie" look that i find cool !

<h6>Terminal-ogp is a <a href="https://dpip.lol" target="_blank">DPIP.lol</a> project.</h6>

## Features

- [X] Themes
- [X] No caching issues
- [X] No 500 errors
- [X] Always return an svg
- [ ] Convert svgs to pngs
- [X] Orgs support
- [ ] Server-side caching
- [ ] Github workflow to auto-update the social preview (note that this isn't possible at this time)

## How to use

### 1. In a ReadMe
To use a terminal opengraph in your readme, take this following code and paste it into your readme:
```md
![Terminal GitOpenGraphImage](https://togp.xyz/?owner=YOUR_USERNAME&repo=YOUR_REPO&avatar=false)
```
This will show the image in your readme, make sure to replace `YOUR_USERNAME` and `YOUR_REPO` by the actual values. Setting avatar to true will add your avatar in the bottom right corner.

> Note => If you need your image to be updated each time you reload the readme, set the parameter `cache` to false (add `&cache=false` in the URL)

## Themes

> [!NOTE]  
> There are several themes of this API, **BUT WE NEED MORE** ! If you got an idea, [please check the tutorial to provide us your very own theme !](themes/PUBLISH.md)

Use `?theme=THEME_NAME` parameter like so:

```md
![togp](https://togp.xyz/?owner=douxxtech&repo=terminal-ogp&theme=json-dark-all)
```

[Check all the themes here !](themes/THEMES.md)

You can also use `?svg=https://example.com/mysvg.svg` if you have a custom svg file.
Adding `?failurl=https://example.com/fallback.svg`: Returns a `Location:` header to the given url if something fails.

## Deployment
To host your very own version of this project, follow those easy steps:

1. Clone this repository

Open a terminal and use git to clone this repository
```shell
git clone https://github.com/douxxtech/terminal-ogp
```

2. Host the files

Go on your PHP webserver server (make sure that php is allowed in the php.ini) and put the files of [/src/](src/) into your file server.

3. Configure the php file

Open the [/src/index.php](src/index.php) and replace `YOUR_GITHUB_TOKEN` with a personal access token with repo permissions.
[Click here to create a personal token](https://github.com/settings/tokens/new)

## FAQ

**Q: Can we contribute to this project ?**  
A: Sure! To do so, fork this repository, make your changes and do a pull request !
**Q: Are you storing users datas ?**  
A: No. We aren't collecting or storing anything  
**Q: How to support you ?**  
A: I don't want any money. But you can star this repo or follow me !

--- 
## Specific content

<h6>err</h6>

## Errors returned by the API

The API may can return an error page if something went wrong or is missing.  

![Error image](https://togp.xyz/svg/error.svg)

The error message will most of the time tell where the error occured, so just follow the instructions to fix it.
However, if the error message doesn't help you, [consider opening an issue](https://github.com/douxxtech/terminal-ogp/issues/new)

## Readme Views

<img src="https://prv-readme-views.dpip.lol/v3_2">

----
Made by douxxtech | douxx.tech | dpip.lol
