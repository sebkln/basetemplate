![Rocket ship symbol](Resources/Public/Icons/Extension.svg)

# Sitepackage Starter Kit for TYPO3 CMS

## Compatibility

TYPO3 12.4.6 - 12.4.99


## What is a TYPO3 sitepackage?

All relevant configuration for your TYPO3 installation should be stored in a custom extension:
the so-called *sitepackage*.

**A sitepackage typically contains:**

- Frontend templates
- Stylesheets and JavaScripts
- Theme images (like logos and icons)
- Any configuration (TypoScript, TSconfig, YAML, …)
- Overrides of third-party extensions (e.g. configuration and templates)
- Overrides of the TYPO3 core, e.g. new database fields
- Custom content elements
- Custom Fluid ViewHelpers and other PHP classes
  (if shared across multiple projects, a *separate* extension for these might be more suitable)

In conclusion, the sitepackage is the control center of your TYPO3 project.

Learn more about sitepackages in general here: https://docs.typo3.org/m/typo3/tutorial-sitepackage/main/en-us/Index.html


## What does this Starter Kit provide?

This TYPO3 extension can be the starting point for your own sitepackage.

It contains enough configuration and templates to get you started.

It will not impose any Frontend Framework on you. You're free to use your favourite Framework and custom templates.

This sitepackage starter kit is maintained and continuosly improved since TYPO3 version 6.2.
Its concepts follow best practices of the TYPO3 community, but also take personal experiences into account.


## Features

- Meaningful directory structure to manage your files
- Basic Fluid templates for the website and the main navigation (easily adjustable)
- Essential **TypoScript** Setup, which you can integrate as a Static Template
- Basic **TSconfig**, e.g. for backend layouts
- Basic RTE **CKEditor 5** configuration
- **Route Enhancers** for pages and news
- PSR-15 middleware to provide favicons and web app manifest
- Configuration files are divided into smaller partials for more clarity and maintainability


## How to use this extension

**Copy this extension into your TYPO3 installation – and then customize it to your needs!**

There is no need to update this extension with a newer version from this repository at a later time.

You may want to **rename** the extension:
1. Rename the folder from `basetemplate` to your desired name, e.g. `clientname`.
   **Keep the naming conventions for extensions in mind!**<sup>[1](#namingconvention)</sup>
2. Search and replace all occurences of `basetemplate` with the new chosen name.
   Replacing is fast and easy if you use a professional text editor and don't use underscores.<sup>[2](#underscores)</sup>


### Installation

- [Installation using Composer](https://docs.typo3.org/m/typo3/tutorial-sitepackage/main/en-us/ExtensionInstallation/Index.html)
- [Installation without Composer](https://docs.typo3.org/m/typo3/tutorial-sitepackage/main/en-us/ExtensionInstallation/InstallationWithoutComposer.html)


### Initial setup

#### 1. Include the TypoScript template

Include the **Static Template** of this extension in your TypoScript root template (`sys_template`).

The TypoScript of all other extensions (Fluid Styled Content, XML sitemap, News, …) should be loaded from
within the sitepackage.

**Configuration/TypoScript/setup.typoscript:**

```
//
// Dependencies
// ------------------------------------------
@import 'EXT:fluid_styled_content/Configuration/TypoScript/setup.typoscript'
@import 'EXT:seo/Configuration/TypoScript/XmlSitemap/setup.typoscript'
#@import 'EXT:news/Configuration/TypoScript/setup.typoscript'


//
// Project setup
// ------------------------------------------
@import 'EXT:basetemplate/Configuration/TypoScript/Config/*.typoscript'
@import 'EXT:basetemplate/Configuration/TypoScript/Helper/*.typoscript'
@import 'EXT:basetemplate/Configuration/TypoScript/Lib/*.typoscript'
@import 'EXT:basetemplate/Configuration/TypoScript/Extensions/*.typoscript'
#@import 'EXT:basetemplate/Configuration/TypoScript/ContentElements/*.typoscript'
@import 'EXT:basetemplate/Configuration/TypoScript/Page/Page.typoscript'
```

*Note: remember to also import an extension's TypoScript constants in `constants.typoscript`, if given*

This allows you to specify the loading order in one central place, to version it with Git
and to deploy it to multiple web servers (e.g. Production, Staging and Development).

#### 2. Include the Page TSconfig

Include the desired **Page TSconfig** resources in the page properties of your root page.

It provides the preconfigured Backend Layouts and various backend configurations.
Be sure to check and adjust these to your needs.

Note: TYPO3 v12 would allow to [include Page TSconfig automatically](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-96614-AutomaticInclusionOfPageTsConfigOfExtensions.html).
This extension requires to include it manually, which allows for different TSconfig settings in a multi-domain installation.

#### 3. Import the Site settings

Import the preconfigured **Site settings** in your `config/sites/<my_site>/settings.yaml` (or copy the related contents):

```
imports:
  - { resource: "EXT:basetemplate/Configuration/Site/Settings/All.yaml" }
```

This file includes some configuration that is read in TypoScript, e.g.:

- page IDs for website navigations
- page IDs for storage folders (e.g. news)
- settings for the favicon provider

Read more about Site settings and their advantages:
https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/SiteHandling/SiteSettings.html

#### 4. Optional: Import preconfigured Route Enhancers

This extension contains commonly used configuration for page types and the news extension.

You can import all or selected Route Enhancers into your `config/sites/<my_site>/config.yaml`
(or copy their configuration):

```
imports:
  -
    resource: 'EXT:basetemplate/Configuration/Site/Routes/All.yaml'
```

Read more about Routing in TYPO3:
https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Routing/Index.html

### Customizing

#### Using Backend layouts and Fluid templates

For every column you create in a Backend layout, you have to set an **individual** `colPos` number.
You should, however, **reuse** these values across your other Backend layouts.
It allows the editors to change the layout of a page while keeping the content in place.

Set the `colPos` values wisely. The content of e.g. the top column shouldn't suddenly move to the bottom
when changing layouts.

![BackendLayout, columns 66-33](Resources/Public/Images/BackendLayouts/BELayout_2_columns_66_33.svg)
![BackendLayout, columns 50-50](Resources/Public/Images/BackendLayouts/BELayout_2_columns_50_50.svg)

The TypoScript `lib.dynamicContent` will take care of rendering the desired column's content
in your Fluid templates.

All you have to do is inserting the following `f:cObject` viewhelper into your Fluid template
and adapt the `colPos` value:

````
<f:cObject typoscriptObjectPath="lib.dynamicContent" data="{colPos: '0'}"/>
````

More details: [TYPO3 Sitepackage tutorial, Chapter 'Content Mapping'](https://docs.typo3.org/m/typo3/tutorial-sitepackage/main/en-us/ContentMapping/Index.html)


#### Overriding third-party extensions

At some point, you'll need to customize templates from extensions like [news](https://extensions.typo3.org/extension/news/).
You should save all these modifications in your sitepackage, too.

I recommend to store them inside the subdirectory `Resources/Private/Extensions/` to separate them from your website templates.

In case of EXT:news, this will result in the following directory structure:

````
Resources/Private/Extensions/news/Templates/
Resources/Private/Extensions/news/Partials/
Resources/Private/Extensions/news/Layouts/
````

You'll have to set these paths in TypoScript. All TypoScript configurations for third party extensions
should be stored in `Configuration/TypoScript/Extensions/[extensionKey].typoscript`.


### Footnotes

<a name="namingconvention">[1]</a> TYPO3 extensions have some naming conventions:
https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ExtensionArchitecture/BestPractises/NamingConventions.html

<a name="underscores">[2]</a> Also **be very careful when using underscores** in your extension name! It is highly encouraged to avoid them.
If you e.g. choose the name `acme_site_package`, you'll have to use **two notations!**
In links like *EXT:acme_site_package/link/to/file.css*, use the actual folder name.
When using constants however, you'll have to remove all underscores and prefix `tx_`: *$plugin.tx_acmesitepackage*!
