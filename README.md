# API Swagger

## Defining Response Types

- `Response`
```json
{
    "code": 404,
    "message": "Not Found"
}
```

- `TokenResponse`
```json
{
    "code": 200,
    "message": "OK",
    "token": "jwt.access.token"
}
```

- `DataResponse<Model>`
```json
{
    "code": 200,
    "message": "OK",
    "data": {Model}
}
```
where `Model` is a JSON object derived from one of the following: `Dish`, `Rating`, `Recipe`, `TokenData`, or a list of such models in which case the `Model` is enclosed in square brackets `[]`.

## Endpoints

By the default the response type is `Response` unless specified.
Note that there is always a possibility for an HTTP response with a status code of `500` if the database connection has a critical error.

### `GET /dish/{id}`
- Request parameters:
    - `id`: The id of the dish.
- Possible status codes and responses:
    - 200: `DataResponse<Dish>`
    - 400
    - 404

### `GET /rating/{id}`
- Request parameters:
    - `id`: The id of the rating.
- Possible status codes and responses:
    - 200: `DataResponse<Rating>`
    - 400
    - 404

### `GET /recipe/{id}`
- Request parameters:
    - `id`: The id of the recipe.
- Possible status codes and responses:
    - 200: `DataResponse<Recipe>`
    - 400
    - 404

### `GET /search/{query}`
- Request parameters:
    - `query`: The text to search for in dishes' names.
- Possible status codes and responses:
    - 200: `DataResponse<[Dish]>`
    - 400

### `POST /create_user`
- Request parameters:
    - `firstname`: `string`
    - `lastname`: `string|null`
    - `email`: `string`
    - `password`: `string`
- Possible status codes and responses:
    - 200: `TokenResponse`
    - 400
    - 500

### `POST /login`
- Request parameters:
    - `email`: `string`
    - `password`: `string`
- Possible status codes and responses:
    - 200: `TokenResponse`
    - 401

### `POST /quick_recommendation`
- Required HTTP headers:
    - `Authorization: Bearer X`, where `X` is the obtained access token.
- Possible status codes and responses:
    - 200: `DataResponse<Dish>`
    - 401

### `POST /rating`
- Required HTTP headers:
    - `Authorization: Bearer X`, where `X` is the obtained access token.
- Request parameters:
    - `recipe_id`: `int`
    - `rating`: `int|null`
    - `comment`: `string`
- Possible status codes and responses:
    - 200
    - 400
    - 401
    - 500

### `POST /recipe`
- Required HTTP headers:
    - `Authorization: Bearer X`, where `X` is the obtained access token.
- Request parameters:
    - `dish_id`: `int`
    - `steps`: `string`
    - `duration`: `int|null`
- Possible status codes and responses:
    - 200
    - 400
    - 401
    - 500

### `POST /update_user`
- Required HTTP headers:
    - `Authorization: Bearer X`, where `X` is the obtained access token.
- Request parameters:
    - `email`: `string`
    - `password`: `string|null`
    - `firstname`: `string|null`
    - `lastname`: `string|null`
- Possible status codes and responses:
    - 200: `TokenResponse`
    - 401

### `POST /validate_token`
- Required HTTP headers:
    - `Authorization: Bearer X`, where `X` is the obtained access token.
- Possible status codes and responses:
    - 200: `DataResponse<TokenData>`
    - 401