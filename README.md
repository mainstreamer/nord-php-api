# Secure Information Storage REST API

### Project setup

* Add `secure-storage.localhost` to your `/etc/hosts`: `127.0.0.1 secure-storage.localhost`

* Run `make init` to initialize project

* Open in browser: http://secure-storage.localhost:8000/item Should get `Full authentication is required to access this resource.` error, because first you need to make `login` call (see `postman_collection.json` or `SecurityController` for more info).

### Run tests

make tests

### API credentials

* User: john
* Password: maxsecure

### Postman requests collection

You can import all available API calls to Postman using `postman_collection.json` file

Request format:

Update items:
---
PUT `http://secure-storage.localhost:8000/items/{itemId}`

Params:

`data : "some data"`

Response: 
Status 200
```{
    "id": 97,
    "data": "some data",
    "createdAt": {
        "date": "2021-10-10 02:01:46.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updatedAt": {
        "date": "2021-10-10 02:16:24.041716",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "userDto": {
        "id": 5,
        "username": "john",
        "createdAt": {
            "date": "2021-10-10 02:01:25.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updatedAt": {
            "date": "2021-10-10 02:01:25.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    }
}`
```

Not found: Status code 404

Unauthorized: Status code 401

Bad Request: Status code 400

