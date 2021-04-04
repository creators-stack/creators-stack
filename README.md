<p align="center"><img src="https://raw.githubusercontent.com/creators-stack/creators-stack/master/resources/svg/logo.svg" width="210" height="210"></p>

<p align="center">
<a href="https://github.com/creators-stack/creators-stack/actions/workflows/tests.yml"><img src="https://github.com/creators-stack/creators-stack/actions/workflows/tests.yml/badge.svg?branch=master" alt="Tests status"></a>
<a href="https://github.com/creators-stack/creators-stack/actions/workflows/docker.yml"><img src="https://github.com/creators-stack/creators-stack/actions/workflows/docker.yml/badge.svg?branch=master" alt="Docker Build Status"></a>
</p>

## Creators Stack

Creators Stack is a web app that let you organize your creators Images and Videos in one place

## Main Features

- Creators & Content Crawling
- Images and Videos Galleries
- Thumbnails and configurable Video Previews

## Docker Compose Usage

First clone the repository
```bash
git clone git@github.com:angauber/creators_stack.git
```

Build the docker image
```bash
docker build -t creators_stack:latest -f docker/prod/Dockerfile .
```

Once the image is build, grab the `docker-compose.yml` file from the repo
```bash
curl https://raw.githubusercontent.com/angauber/creators_stack/master/docker/prod/docker-compose.yml > docker-compose.yml
```

Open `docker-compose.yml` file and edit the `creators_stack` environment variables to match your environment.

Finally, run the container using
```bash
docker-compose up -d
```

If you suspect something is not working, you can check the container logs using
```bash
docker-compose logs
```

You should be able to access the web interface at `http://server_ip:7667`
The default credentials are `admin` `admin`
You can change them in the profile tab

## Development
Docker is also used for development through laravel [Sail](https://laravel.com/docs/8.x/sail#introduction)  
First clone the project then run the following commands to setup your `.env` and install composer dependencies
```bash
cp .env.example .env
docker run --rm -v $(pwd):/opt -w /opt laravelsail/php80-composer:latest composer install
```

Once the dependencies are installed you can use the sail binary to build and run the container
```bash
sail up -d
```

Run tests
```bash
sail test
```

Run Code Quality Tests
```bash
sail artisan insights
```

## License
Creators Stack is  an open source software licensed under the [GPL-3.0](https://opensource.org/licenses/GPL-3.0) license.
