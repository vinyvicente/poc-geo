<?php

require 'vendor/autoload.php';

$geoCoder = new \Geocoder\ProviderAggregator();
$adapter  = new \Ivory\HttpAdapter\CurlHttpAdapter();

$geoCoder->registerProviders([
    new \Geocoder\Provider\GoogleMaps($adapter),
]);

$results = null;

try {
    $tools = new \League\Geotools\Geotools();
    $results  = $tools->batch($geoCoder)->geocode([
        'Rua do Mecânico, 1124, Uberlândia, Minas Gerais',
        'Estrada do Tambory, 1395, Carapicuíba, São Paulo'
    ])->parallel();

} catch (\Exception $e) {
    die($e->getMessage());
}

if (!empty($results)) {
    $dumper = new \Geocoder\Dumper\Wkt();
    /**
     * @var League\Geotools\Batch\BatchGeocoded $result
     */
    foreach ($results as $result) {
        printf("%s|%s|%s\n",
            $result->getProviderName(),
            $result->getQuery(),
            '' == $result->getExceptionMessage() ? $dumper->dump($result->getAddress()) : $result->getExceptionMessage()
        );
    }
}
