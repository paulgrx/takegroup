### Requirements
Ubuntu with Docker installed.

### Run on local machine
1. Inside the `app` folder, create a `.env` file:
```
cp .env.example .env
```
2. Inside the `docker` folder, create the network, build the image, and run the project:
```
./create-network.sh
./build.sh
./up.local.sh
```

The project will be available at [http://localhost:8000](http://localhost:8000)

### API endpoints

#### Base URL
[http://localhost:8000/api](http://localhost:8000/api)

All list endpoints support pagination and multi-language responses based on the Accept-Language HTTP header

1. `/movies` - list all movies
2. `/series` - list all series
3. `/genres` - list all genres