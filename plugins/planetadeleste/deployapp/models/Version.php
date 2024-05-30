<?php

namespace PlanetaDelEste\DeployApp\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use October\Rain\Argon\Argon;
use October\Rain\Database\Builder;
use October\Rain\Database\QueryBuilder;
use October\Rain\Database\Relations\BelongsTo;

/**
 * Class Version.
 *
 * @mixin Builder
 * @mixin Eloquent
 *
 * @property int    $id
 * @property int    $frontapp_id
 * @property string $version
 * @property string $description
 * @property Argon  $created_at
 * @property Argon  $updated_at
 * @property bool   $is_app_valid
 *
 * Relations
 * @property FrontApp $frontapp
 *
 * @method BelongsTo|FrontApp frontapp()
 */
class Version extends \Model
{
    protected static array $arListVersions = [];

    /** @var string */
    public $table = 'planetadeleste_deployapp_versions';

    /** @var array */
    public $rules = ['version' => 'required'];

    /** @var array */
    public $fillable = ['version', 'description', 'frontapp_id'];

    /** @var array */
    public $cached = [
        'id',
        'version',
        'description',
        'frontapp_id',
    ];

    /** @var array */
    public $dates = [
        'created_at',
        'updated_at',
    ];
    /** @var array */
    public $belongsTo = [
        'frontapp' => [FrontApp::class],
    ];

    /**
     * @param int $iFrontAppId
     *
     * @return Model|static|QueryBuilder|null
     */
    public static function getLatest($iFrontAppId)
    {
        return static::where('frontapp_id', $iFrontAppId)->orderByDesc(static::rawColumn())->first();
    }

    /**
     * @param int $iFrontAppId
     *
     * @return string|null
     */
    public static function getLatestVersion($iFrontAppId)
    {
        $obModel = static::getLatest($iFrontAppId);

        return $obModel ? $obModel->version : null;
    }

    /**
     * @return mixed
     */
    public static function rawColumn()
    {
        if ('pgsql' == config('database.default')) {
            return \Db::raw("string_to_array(version, '.')::int[]");
        }

        return \Db::raw("INET_ATON(SUBSTRING_INDEX(CONCAT(version,'.0.0.0'),'.',4))");
    }

    public function getVersionOptions(): array
    {
        if (!$this->frontapp || !($this->frontapp instanceof FrontApp)) {
            return [];
        }

        return $this->frontapp->listVersions();
    }

    public function getStoredVersions(): array
    {
        if (!$this->frontapp_id || (!$obApp = FrontApp::find($this->frontapp_id))) {
            return [];
        }

        if (empty(self::$arListVersions)) {
            self::$arListVersions = $obApp->listAllVersions();
        }

        return self::$arListVersions;
    }

    public function getIsAppValidAttribute(): bool
    {
        return in_array($this->version, $this->getStoredVersions());
    }
}
