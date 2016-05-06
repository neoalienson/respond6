# Respond CMS Docker Image

## The docker image
You can either pull the docker image from DockerHub or build it yourself from
DockerFile locally

### Building from DockerFile

``` bash
$ docker build -t respond .
```

### Pulling from DockerHub

```bash
$ docker pull neoalienson/respond6
```

## Starting a container from the docker image
After the image is ready, you can start a container from the docker image. 
 
### Run without data volumes
```bash
$ docker run --name=respond -p 80:80 respond
```

### Run with data volume

Run below commands if you want to store site contents into your docker host instead of inside the docker image.
Please check [data volumes](https://docs.docker.com/engine/userguide/containers/dockervolumes/) for details.

``` bash
$ DATA_DIR=${HOME}/respond-data
$ mkdir -p ${DATA_DIR}/sites 
$ mkdir -p ${DATA_DIR}/resources 
$ docker run --name=respond -p 80:80 \
-v ${DATA_DIR}/sites:/var/www/public/sites:Z \
-v ${DATA_DIR}/resources:/var/www/resources/:Z \
respond
```

