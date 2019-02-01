# Monitoring

## Install

Monitoring contains a library with the name Heimdall.
It aim to give you the way to monitor informations in shippeo applications.

### Add the library to your dependencies

The first thing you need to do is require the library in you project.

1 . add the following to your composer.json file.

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/shippeo/monitoring"
        }
    ]
}
```

2 . run the following command.

```bash
composer require shippeo/monitoring
```

## Basic Usage

With instances of `Database`: $database1, $database2 and instance of `Metric` $metric .
```php
(new AddMetric([$database1, $database2]))($metric);
```

## Integration with other libraries

The library is not dependant of any framework. It has nontheless a bridge system to allow an easier integration.

### Using the Symfony bridge

The bridge also provide extra features out of the box as the request subscriber (monitor requests).

To activate it
* add the bundle to your kernel
```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
    
            new \Shippeo\Heimdall\Bridge\Symfony\Bundle\MonitoringBundle(),
        ];
    }
}
```

* add the corresponding configuration
```yaml
monitoring:
    statsD:
        host: 'The host of your statsD server'
        port: 8125 # The port to connect to your statsD server in UDP
```

### Add a metric
Use the `\Shippeo\Heimdall\Application\AddMetric` service. it is a public service that can directly take a metric.

> Your databases should already be configured by the bundle.

## Domain

### Ubiquitous Language

A **Database** in this project refers to something which has the ability to store a **Metric**.

**Measurement** is the equivalent of a table in a relational database such as MySQL.
It is a lot more permissive than a classic "table" as it is created automatically and does not need all the fields to be filled.

**Metric** refers to a single send of information 


## Current stack

### Stack
We are using the TIG stack. It stands for Telegraf, InfluxDB, Grafana.
It is an open source stack for collecting and visualizing data.

#### [Telegraf](https://www.influxdata.com/time-series-platform/telegraf/)
It is an agent for collecting metrics and writing them.
It has the advantage of being able to be plugged on various sources both in inputs and outputs.

#### [InfluxDB](https://www.influxdata.com/time-series-platform/influxdb/)
It is a time series database.

#### [Grafana](https://grafana.com/)
It is an open source data visualization tool.
It allow us to query the database to draw graphs and display them across dashboards.
We can configure alert on top of each graph.

### Usage

#### How to send metrics
We send metrics by UDP to telegraf. It embedded a StatsD to collect and parse the metrics.

#### Debug
To simulate the send of an information to the StatsD listener over UDP
You can send it via the following command:
```bash
echo "{{key}},{{tag1Name}}={{tag1Value}},{{tag2Name}}={{tag2Value}}:{{value}}|{{type}}" | nc -u -w1 localhost 8125
```

##### Examples
To send an increment value of `1` for an `api.request`
```bash
echo "api.request:1|c" | nc -u -w1 localhost 8125
```

To add the related endpoint name (`add_position`), user (`4L2WPQNR`) and organization (`WX24J627`)
```bash
echo "api.request,endpoint=add_position,user=4L2WPQNR,organization=WX24J627:1|c" | nc -u -w1 localhost 8125
```
