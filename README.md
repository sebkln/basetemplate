![Rocket ship symbol](Resources/Public/Icons/Extension.svg)

# Sitepackage Starter Kit for TYPO3 CMS

## Compatibility

Since some configurations have changed between TYPO3 versions, you can choose the compatible version of this extension
here:

- [basetemplate for TYPO3 v13 (main branch)](https://github.com/sebkln/basetemplate/)
- [basetemplate for TYPO3 v12](https://github.com/sebkln/basetemplate/tree/12.4)

## What is a TYPO3 sitepackage?

All relevant configurations for your TYPO3 installation should be stored in a custom extension:
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

It will not impose any Frontend Framework on you. You're free to use your favorite Framework and custom templates.

This sitepackage starter kit has been maintained and continuously improved since TYPO3 version 6.2.
Its concepts follow the best practices of the TYPO3 community but also take personal experiences into account.


## Features

- Meaningful directory structure to manage your files
- Basic Fluid templates for the website and the main navigation (easily adjustable)
- Essential **TypoScript** Setup, which you can integrate as a [Site Set](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/SiteHandling/SiteSets.html)
- Basic **TSconfig**, e.g. for backend layouts
- Basic RTE **CKEditor 5** configuration
- **Route Enhancers** for pages and news
- PSR-15 middleware to provide favicons and web app manifest
- Configuration files are divided into smaller partials for more clarity and maintainability


## How to use this extension

**Copy this extension into your TYPO3 installation – and then customize it to your needs!**

There is no need to update this extension with a newer version from this repository at a later time.

You might want to **rename** the extension:
1. Rename the folder from `basetemplate` to your desired name, e.g. `clientname`.
   **Keep the naming conventions for extensions in mind!**<sup>[1](#namingconvention)</sup>
2. Search and replace all occurences of `basetemplate` with the new chosen name.
   Replacing is fast and easy if you use a professional text editor and don't use underscores.<sup>[2](#underscores)</sup>

Be sure to check and adjust the configurations to your needs.

### Installation

- [Installation using Composer](https://docs.typo3.org/m/typo3/tutorial-sitepackage/main/en-us/ExtensionInstallation/Index.html)
- [Installation without Composer](https://docs.typo3.org/m/typo3/tutorial-sitepackage/main/en-us/ExtensionInstallation/InstallationWithoutComposer.html)


### Initial setup

#### 1. Include the Site Set

Include the **Site Set** "Basetemplate (Sitepackage)" of this extension in your Site Configuration.

This will provide various configurations for the related page tree:

- TypoScript
- Page TSconfig
- Site Settings

Included by default are the following **Site Set dependencies**:

- Basetemplate (Favicon configuration)
- Fluid Styled Content
- SEO Sitemap

_Note: **User TSconfig** is loaded globally when this sitepackage is installed._

Learn more about the new Site Sets in TYPO3:
https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/SiteHandling/SiteSets.html

#### 2. Adjust the Site settings

##### Site Setting definitions

1. `Configuration/Sets/Basetemplate/settings.definitions.yaml`\
   Settings for the website, e.g. page identifiers
2. `Configuration/Sets/Favicons/settings.definitions.yaml`\
   Settings for the favicon provider

**Default values** are configured in these definitions.

##### Applied Site Settings (subset)

A subset allows to override default values from dependencies, as well as your custom configuration.

Bundle all actual Site Setting values in the subset file:\
`Configuration/Sets/Basetemplate/settings.yaml`

By default, the following settings are shipped:

- Favicons
- Fluid Styled Content (selected settings)
- Custom page identifiers

Read more about Site Settings in TYPO3:
https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/SiteHandling/SiteSettings.html

#### 3. Optional: Import preconfigured Route Enhancers

This extension contains commonly used configurations for page types and the news extension.

You can import all or selected Route Enhancers into your `config/sites/<my_site>/config.yaml`
(or copy their configuration):

```
imports:
  -
    resource: 'EXT:basetemplate/Configuration/Site/Routes/All.yaml'
```

Read more about Routing in TYPO3:
https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Routing/Index.html

#### 4. Load configuration of dependencies (extensions)

The TypoScript of all third-party extensions (Fluid Styled Content, XML sitemap, News, …) should be loaded from
within the sitepackage. There are two options:

1. Extensions that already support Site Sets should be added as dependencies in the Site Configuration.

    `Configuration/Sets/Basetemplate/config.yaml`:

    ```
    name: sebkln/basetemplate
    label: Basetemplate (Sitepackage)
    dependencies:
      - sebkln/basetemplate-favicons
      - typo3/fluid-styled-content
      - typo3/seo-sitemap
    ```

2. For other extensions, include their TypoScript directly before your project's TypoScript.

    `Configuration/Sets/Basetemplate/setup.typoscript`:

    ```
    //
    // Dependencies
    // ------------------------------------------
    #@import 'EXT:news/Configuration/TypoScript/setup.typoscript'


    //
    // Project setup
    // ------------------------------------------
    @import 'EXT:basetemplate/Configuration/Sets/Basetemplate/TypoScript/Config/*.typoscript'
    @import 'EXT:basetemplate/Configuration/TypoScript/Helper/*.typoscript'
    @import 'EXT:basetemplate/Configuration/Sets/Basetemplate/TypoScript/Lib/*.typoscript'
    @import 'EXT:basetemplate/Configuration/Sets/Basetemplate/TypoScript/Extensions/*.typoscript'
    #@import 'EXT:basetemplate/Configuration/Sets/Basetemplate/TypoScript/ContentElements/*.typoscript'
    @import 'EXT:basetemplate/Configuration/Sets/Basetemplate/TypoScript/Page/Page.typoscript'
    ```

    *Note: remember to also import an extension's TypoScript constants in `constants.typoscript`, if given*

This allows you to specify the loading order in one central place, to version it with Git
and to deploy it to multiple web servers (e.g. Production, Staging, and Development).


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

All you have to do is insert the following `f:cObject` viewhelper into your Fluid template
and adapt the `colPos` value:

````
<f:cObject typoscriptObjectPath="lib.dynamicContent" data="{colPos: '0'}"/>
````

More details: [TYPO3 Sitepackage tutorial, Chapter 'Content Mapping'](https://docs.typo3.org/m/typo3/tutorial-sitepackage/main/en-us/ContentMapping/Index.html)


#### Overriding third-party extensions

At some point, you'll need to customize templates from extensions like [news](https://extensions.typo3.org/extension/news/).
You should save all these modifications in your sitepackage, too.

I recommend storing them inside the subdirectory `Resources/Private/Extensions/` to separate them from your website templates.

In the case of EXT:news, this will result in the following directory structure:

````
Resources/Private/Extensions/news/Templates/
Resources/Private/Extensions/news/Partials/
Resources/Private/Extensions/news/Layouts/
````

You'll have to set these paths in TypoScript. All TypoScript configurations for third-party extensions
should be stored in `Configuration/Sets/Basetemplate/TypoScript/Extensions/[extension_key].typoscript`.


### Footnotes

<a name="namingconvention">[1]</a> TYPO3 extensions have some naming conventions:
https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ExtensionArchitecture/BestPractises/NamingConventions.html

<a name="underscores">[2]</a> Also **be very careful when using underscores** in your extension name! It is highly encouraged to avoid them.
If you e.g. choose the name `acme_site_package`, you'll have to use **two notations!**
In links like *EXT:acme_site_package/link/to/file.css*, use the actual folder name.
When using constants, however, you'll have to remove all underscores and prefix `tx_`: *$plugin.tx_acmesitepackage*!
