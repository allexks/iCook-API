# API Swagger

## Endpoints

By the default the response type is `Response` unless specified.
Note that there is always a possibility for an HTTP response with a status code of `500` if the database connection has a critical error.

### `GET /dish/{id}`
- Request parameters:
    - `id`: The id of the dish.
- Possible status codes and responses:
    - 200: `DataResponse<DishDTO>`
    - 400
    - 404

### `GET /rating/{id}`
- Request parameters:
    - `id`: The id of the rating.
- Possible status codes and responses:
    - 200: `DataResponse<RatingDTO>`
    - 400
    - 404

### `GET /recipe/{id}`
- Request parameters:
    - `id`: The id of the recipe.
- Possible status codes and responses:
    - 200: `DataResponse<RecipeDTO>`
    - 400
    - 404

### `GET /search/{query}`
- Request parameters:
    - `query`: The text to search for in dishes' names.
- Possible status codes and responses:
    - 200: `DataResponse<[DishDTO]>`
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
    - 200: `DataResponse<DishDTO>`
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
    - 200: `DataResponse<UserDataDTO>`
    - 401

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

- `DataResponse<DTO>`
```json
{
    "code": 200,
    "message": "OK",
    "data": DTO
}
```
where `DTO` is one of the following types: `DishDTO`, `RatingDTO`, `RecipeDTO`, `UserDataDTO`, or a list of such models in which case the `ModelDTO` is enclosed in square brackets `[]`.


## DTOs

- `DishDTO`
```json
{
    "id": 0,
    "name": "string",
    "description": "string",
    "image_url": "string",
    "recipes": [RecipeDTO]
}
```

- `RecipeDTO`
```json
{
    "id": 0,
    "dish_id": 0,
    "user_id": 0,
    "date_created": 0, // unix time
    "duration": 0, // minutes
    "steps": "string",
    "user_names": "string",
    "user_email": "string",
    "ratings": [RatingDTO]
}
```

- `RatingDTO`
```json
{
    "id": 0,
    "recipe_id": 0,
    "user_id": 0,
    "rating": 0, // int in the range [0, 10]
    "comment": "string"|null,
    "user_names": "string",
    "user_email": "string"
}
```

- `UserDataDTO`
```json
{
    "id": 0,
    "firstname": "string",
    "lastname": "string",
    "email": "string",
}
```