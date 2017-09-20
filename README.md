# yii2-example-api

Example of creating an application for the API on the basis of Yii2.

## installing

```
git clone git@github.com:kuaukutsu/yii2-example-api.git
```

## installing Yii

```
cd ./www
composer global require "fxp/composer-asset-plugin:^1.3.1"
composer install --no-dev
composer run post-create-project-cmd
```

## Example

### REST

```
GET,POST https://rest.localhost/v1/user
```

### AJAX

```
GET,POST https://rest.localhost/ajax/user?token=xxx
```

### GraphQL

```
GET,POST https://rest.localhost/graphql
```

#### Query
```
query {
  user(id:1) {
    username 
  }
}
```

#### Modify
```
mutation {
  user(id:1) {
    save(input:{username:"test"}) {
      username
    }  
  }
}
```

### Result

Success:
```json
{
    "success": true,
    "data": {
        "user": {
            "save": {
                "username": "test"
            }
        }
    }
}
```

Error:
```json
{
    "success": false,
    "errors": [
        [
            {
                "name": "username",
                "message": [
                    "Необходимо заполнить «Username»."
                ]
            }
        ]
    ]
}
```