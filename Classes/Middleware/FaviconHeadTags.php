<?php

namespace Sebkln\Basetemplate\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileException;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Renders a web manifest, favicons and touch icons in the HTML head.
 *
 * ## Notes
 * You no longer need to provide a "favicon.ico", as the PNG format is supported in all modern browsers (and IE11).
 * See: https://caniuse.com/?search=favicon
 *
 * ## Configuration
 *
 * Configuration is done in `config/<site-identifier>/settings.yaml`:
 * ```
 * favicons:
 *   full_name: 'My fancy website'
 *   short_name: 'My website'
 *   theme_color: '#ff8800'
 *   favicon_path: 'EXT:basetemplate/Resources/Public/Icons/Favicons/'
 *   favicon_svg: favicon.svg
 *   favicon_png: favicon-32x32.png
 *   apple_touch_icon: apple-touch-icon.png
 *   android_192: android-chrome-192x192.png
 *   android_512: android-chrome-512x512.png
 * ```
 * All settings are optional.
 * If no favicon path is set, no favicons will be linked in meta tags or the web app manifest.
 * A meta tag is rendered for each favicon file you configured.
 * A given theme color will be rendered both as meta tag and in the web app manifest.
 *
 * Original source: https://typo3.slack.com/archives/C025BQLFA/p1680299950579649
 */
class FaviconHeadTags implements MiddlewareInterface
{
    protected ServerRequestInterface $request;

    final const MANIFEST_PATH = '/site.webmanifest';

    /**
     * @throws InvalidFileException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $this->request = $request;
        if ($this->isWebmanifestRequest()) {
            ob_clean();
            return (new JsonResponse())->setPayload($this->generateWebmanifest());
        }

        $this->addMetatagsToHead();

        return $handler->handle($this->request);
    }

    protected function isWebmanifestRequest(): bool
    {
        return $this->request->getUri()->getPath() === self::MANIFEST_PATH;
    }

    /**
     * @return array{background_color: string, display: string, icons: never[]|array<int, array{src: mixed, type: string, sizes: string}>, name?: mixed, short_name?: string, theme_color?: mixed}
     * @throws InvalidFileException
     */
    protected function generateWebmanifest(): array
    {
        $siteConfig = $this->getSiteSettings();
        $webmanifest = [
            'background_color' => '#ffffff',
            'display' => 'browser',
            'icons' => [],
        ];

        $name = $siteConfig['fullName'] ?? '';
        if (!empty($name)) {
            $webmanifest['name'] = $name;
        }

        $shortName = $siteConfig['shortName'] ?? '';
        if (!empty($shortName)) {
            $webmanifest['short_name'] = $shortName;
        }

        $themeColor = $siteConfig['themeColor'] ?? '';
        if (!empty($themeColor)) {
            $webmanifest['theme_color'] = $themeColor;
        }

        $faviconPath = $siteConfig['faviconPath'] ?? '';
        $android192 = $siteConfig['android192'] ?? '';
        $android512 = $siteConfig['android512'] ?? '';
        if (!empty($faviconPath)) {
            if (!empty($android192)) {
                $iconPath192 = PathUtility::getPublicResourceWebPath($faviconPath . $android192);
                $webmanifest['icons'][] = ['src' => $iconPath192, 'type' => 'image/png', 'sizes' => '192x192'];
            }

            if (!empty($android512)) {
                $iconPath512 = PathUtility::getPublicResourceWebPath($faviconPath . $android512);
                $webmanifest['icons'][] = ['src' => $iconPath512, 'type' => 'image/png', 'sizes' => '512x512'];
            }
        }

        return $webmanifest;
    }

    /**
     * @throws InvalidFileException
     */
    protected function addMetatagsToHead(): void
    {
        $headerData = '<link rel="manifest" href="' . self::MANIFEST_PATH . '">';

        $siteConfig = $this->getSiteSettings();
        $faviconPath = $siteConfig['faviconPath'] ?? '';
        $faviconPng = $siteConfig['faviconPng'] ?? '';
        $faviconSvg = $siteConfig['faviconSvg'] ?? '';
        $appleTouchIcon = $siteConfig['appleTouchIcon'] ?? '';
        if (!empty($faviconPath)) {
            if (!empty($faviconSvg)) {
                $iconPathSvg = PathUtility::getPublicResourceWebPath($faviconPath . $faviconSvg);
                $headerData .= '<link rel="icon" href="' . $iconPathSvg . '" type="image/svg+xml">';
            }

            if (!empty($faviconPng)) {
                $iconPathIco = PathUtility::getPublicResourceWebPath($faviconPath . $faviconPng);
                $headerData .= '<link rel="icon" href="' . $iconPathIco . '" type="image/png" sizes="32x32">';

            }

            if (!empty($appleTouchIcon)) {
                $iconPathApple = PathUtility::getPublicResourceWebPath($faviconPath . $appleTouchIcon);
                $headerData .= '<link rel="apple-touch-icon" href="' . $iconPathApple . '" sizes="180x180">';
            }
        }

        $themeColor = $siteConfig['themeColor'] ?? '';
        if (!empty($themeColor)) {
            $headerData .= '<meta name="theme-color" content="' . $themeColor . '">';
        }

        GeneralUtility::makeInstance(PageRenderer::class)->addHeaderData($headerData);
    }

    /**
     * @return array{fullName: ?string,shortName: ?string,themeColor: ?string,faviconPath: ?string,faviconPng: ?string,faviconSvg: ?string,appleTouchIcon: ?string,android192: ?string,android512: ?string}
     */
    protected function getSiteSettings(): array
    {
        /** @var Site $site */
        $site = $this->request->getAttribute('site');
        $settings = $site->getSettings()->getAllFlat();
        return [
            'fullName' => $settings['favicons.full_name'] ?? null,
            'shortName' => $settings['favicons.short_name'] ?? null,
            'themeColor' => $settings['favicons.theme_color'] ?? null,
            'faviconPath' => $settings['favicons.favicon_path'] ?? null,
            'faviconPng' => $settings['favicons.favicon_png'] ?? null,
            'faviconSvg' => $settings['favicons.favicon_svg'] ?? null,
            'appleTouchIcon' => $settings['favicons.apple_touch_icon'] ?? null,
            'android192' => $settings['favicons.android_192'] ?? null,
            'android512' => $settings['favicons.android_512'] ?? null,
        ];
    }
}
