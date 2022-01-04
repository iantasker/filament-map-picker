<?php

namespace Humaidem\FilamentMapPicker\Fields;

use Filament\Forms\Components\Field;
use Humaidem\FilamentMapPicker\Interfaces\MapOptions;

class OSMMap extends Field implements MapOptions
{
    /**
     * Field view
     * @var string
     */
    public string $view = 'filament-map-picker::fields.osm-map-picker';

    /**
     * Main field config variables
     * @var array
     */
    private array $mapConfig = [
        'statePath'  => '',
        'draggable'  => false,
        'zoom'       => 19,
        'maxZoom'    => 20,
        'showMarker' => false,
        'tilesUrl'   => 'http://tile.openstreetmap.org/{z}/{x}/{y}.png',
    ];

    /**
     * Leaflet controls variables
     * @var array
     */
    private array $controls = [
        'zoomControl'     => false,
        'scrollWheelZoom' => 'center',
        'doubleClickZoom' => 'center',
        'touchZoom'       => 'center',
    ];

    /**
     * Extra leaflet controls variables
     * @var array
     */
    private array $extraControls = [];

    /**
     * Create json configuration string
     * @return string
     */
    public function getMapConfig(): string
    {
        return json_encode([
            ...$this->mapConfig,
            ...[
                'statePath' => $this->getStatePath(),
                'controls'  => [
                    ...$this->controls,
                    ...$this->extraControls
                ]
            ]
        ]);
    }

    /**
     * Determine if user can drag map around or not.
     * @param bool $draggable
     * @return MapOptions
     * @note Default value is false
     */
    public function draggable(bool $draggable = true): self
    {
        $this->mapConfig['draggable'] = $draggable;
        return $this;
    }

    /**
     * Set default zoom
     * @param int $zoom
     * @return MapOptions
     * @note Default value 19
     */
    public function zoom(int $zoom): self
    {
        $this->mapConfig['zoom'] = $zoom;
        return $this;
    }

    /**
     * Set max zoom
     * @param int $maxZoom
     * @return $this
     * @note Default value 20
     */
    public function maxZoom(int $maxZoom): self
    {
        $this->mapConfig['maxZoom'] = $maxZoom;
        return $this;
    }

    /**
     * Determine if marker is visible or not.
     * @param bool $show
     * @return $this
     * @note Default value is false
     */
    public function showMarker(bool $show = true): self
    {
        $this->mapConfig['showMarker'] = $show;
        return $this;
    }

    /**
     * Set tiles url
     * @param string $url
     * @return $this
     * @note refer to https://www.spatialbias.com/2018/02/qgis-3.0-xyz-tile-layers/
     */
    public function tilesUrl(string $url): self
    {
        $this->mapConfig['tilesUrl'] = $url;
        return $this;
    }

    /**
     * Determine if zoom box is visible or not.
     * @param bool $show
     * @return $this
     * @note Default value is false
     */
    public function showZoomControl(bool $show = true): self
    {
        $this->mapConfig['controls']['zoomControl'] = $show;
        return $this;
    }

    /**
     * Append extra controls to be passed to leaflet map object
     * @param array $control
     * @return $this
     */
    public function extraControl(array $control): self
    {
        $this->extraControls = [...$this->extraControls, ...$control];
        return $this;
    }

    public function hasJs(): bool
    {
        return true;
    }

    public function jsUrl(): string
    {
        $manifest = json_decode(file_get_contents(__DIR__ . '/../../dist/mix-manifest.json'), true);
        return url($manifest['/humaidem/map-picker/map-picker.js']);
    }

    public function hasCss(): bool
    {
        return true;
    }

    public function cssUrl(): string
    {
        $manifest = json_decode(file_get_contents(__DIR__ . '/../../dist/mix-manifest.json'), true);
        return url($manifest['/humaidem/map-picker/map-picker.css']);
    }

    /**
     * Setup function
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->default(['lat' => 0, 'lng' => 0]);
        // TODO to be removed
//        $this->mutateDehydratedStateUsing = function ($state) {
//            if (!($state instanceof Point))
//                return new Point($state['lat'], $state['lng']);
//
//            return $state;
//        };
//
//        $this->afterStateUpdated = function ($state) {
//            if ($state instanceof Point) {
//                /** @var Point $state */
//                $this->state(['lat' => $state->getLat(), 'lng' => $state->getLng()]);
//            }
//        };
    }
}
