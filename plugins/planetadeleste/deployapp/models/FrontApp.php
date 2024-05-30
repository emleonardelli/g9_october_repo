<?php

namespace PlanetaDelEste\DeployApp\Models;

use Kharanenka\Scope\NameField;
use Lovata\Toolbox\Traits\Helpers\TraitCached;
use October\Rain\Database\Traits\Validation;

/**
 * Class FrontApp.
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property int                       $id
 * @property string                    $name
 * @property string                    $path
 * @property bool                      $resources
 * @property \October\Rain\Argon\Argon $created_at
 * @property \October\Rain\Argon\Argon $updated_at
 */
class FrontApp extends \Model
{
    use Validation;
    use NameField;
    use TraitCached;

    /** @var string */
    public $table = 'planetadeleste_deployapp_frontapps';

    /** @var array */
    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    /** @var array */
    public $translatable = [
        'name',
    ];

    /** @var array */
    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
    ];

    /** @var array */
    public $rules = [
        'name' => 'required',
        'path' => 'required',
    ];

    /** @var array */
    public $fillable = [
        'name',
        'path',
        'resources',
    ];

    /** @var array */
    public $cached = [
        'id',
        'name',
        'path',
        'resources',
    ];

    /** @var array */
    public $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = ['resources' => 'boolean'];

    protected function getPath(): ?string
    {
        if (!$this->path) {
            return null;
        }

        if ($this->resources) {
            $sPath = resource_path('views/'.$this->name);
        } else {
            $arPath = [
                rtrim($this->path, '/'),
                'assets',
                str_slug($this->name),
            ];

            $sPath = plugins_path(join('/', $arPath));
        }

        return $sPath;
    }

    public function listAllVersions(): array
    {
        if (!$sPath = $this->getPath()) {
            return [];
        }

        if (!\File::exists($sPath)) {
            return [];
        }

        return array_map(
            function ($sPath) {
                $arParts = explode('/', $sPath);

                return array_pop($arParts);
            },
            \File::directories($sPath)
        );
    }

    public function listVersions(): array
    {
        if (!$sPath = $this->getPath()) {
            return [];
        }

        if (!\File::exists($sPath)) {
            return [];
        }

        $arCurrentVersionList = Version::where('frontapp_id', $this->id)->lists('version');
        $arVersionList = $this->listAllVersions();
        $arVersionList = array_diff($arVersionList, $arCurrentVersionList);

        if (!empty($arVersionList)) {
            usort($arVersionList, 'version_compare');
            $arVersionList = array_reverse($arVersionList);

            return array_combine(array_values($arVersionList), array_values($arVersionList));
        }

        return [];
    }
}
