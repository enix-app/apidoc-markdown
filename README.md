# EnixApp - Apidoc

Serve your PHP files in local and see the documentation.

Currently (only) support markdown.

## Installation

Download or clone this project and place them outside or separate from your main project directory for the first time.

```
sample/
∟ apidoc/
  ∟ build/
  ∟ markdown/
    ∟ document.md
    ∟ index.md
  ∟ config.json
∟ apidoc.phar
∟ your-directory-contains-php-files/
```

### Install Chrome Extension

Recomended for you to install Chrome extensions in your favorite web browser (based on chromium):

1. [Markdown Viewer](https://chrome.google.com/webstore/detail/markdown-viewer/ckkdlimhmcjmikdlpkmbgfkaikojcbjk)
2. [JSON Viewer](https://github.com/tulios/json-viewer)

## Config File

See: ``./apidoc/config.json``

Example:

```json
{
	"directory": "your-directory-contains-php-files",
	"exclude": [
		"bin"
	],
	"hideModifiers": {
		"constant": ["private", "protected"],
		"property": ["private", "protected"],
		"method": ["private", "protected"]
	},
	"hideElements": ["source", "params"]
}
```

## Command Line Usage

Serve files in local server:

```
$ php apidoc.phar doc:serve
```

Generate documentation (markdown files):

```
$ php apidoc.phar doc:build
```

## Templating (Markdown)

Require files:

1. index.ext
2. document.ext

You can modify them with your own style. Support Twig-PHP syntax.

## Generated Documents

See directory ``./apidoc/build/api/``
