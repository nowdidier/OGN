<?php


declare(strict_types=1);

namespace Hleb\Base;

use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Constructor\Data\SystemSettings;

#[AvailableAsParent]
abstract class PageController extends Controller
{

    public ?string $themeColor = null;

    public ?string $viewportContent = 'width=device-width, initial-scale=1.0';

    public ?string $title = null;

    public ?string $language = null;

    public ?string $description = null;

    public ?string $faviconUri = '/favicon.ico';

    public ?string $logoUri = null;

    public array $cssResources = [];

    public array $jsResources = [];

    public array $metaRows = [];

    public string $showcaseCenterHtml = '<!-- Showcase center -->';

    public string $showcaseRightHtml = '<!-- Showcase right -->';

    final public function getHeadData(): array
    {
        return [
            'themeColor' => $this->themeColor,
            'title' => $this->title,
            'description' => $this->description,
            'faviconUri' => $this->faviconUri,
            'logoUri' => $this->logoUri,
            'lang' => $this->language,
            'viewportContent' => $this->viewportContent,
            'cssResources' => \array_values($this->cssResources),
            'jsResources' => \array_values($this->jsResources),
            'metaRows' => \array_values($this->metaRows),
            'showcaseRight' => $this->showcaseRightHtml,
            'showcaseCenter' => $this->showcaseCenterHtml,
        ];
    }

    final public function getExternalAccess(): bool
    {
        if (!SystemSettings::getSystemValue('page.external.access')) {
            if (!SystemSettings::getRealPath('@library/hlogin')) {
                return false;
            }
            if (!\Phphleb\Hlogin\App\RegType::check(\Phphleb\Hlogin\App\RegType::REGISTERED_ADMIN, '>=')) {
                return false;
            }
        }
        return true;
    }
}
