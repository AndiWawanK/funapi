
## About FunAPI

FunAPI is a simple API for testing or learn a new frontend framework. FunAPI made with laravel🔥 



#### List of endpoints 🍕

| Method | Endpoint                   | Description                                                                                                                                             | Status Code |
|--------|----------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|-------------|
| POST   | ```api/v1/login```                 | User Login                                                                                                                                              | 200         |
| POST   | ```api/v1/register```            | User Registration                                                                                                                                       | 201         |
| GET    | ```api/v1/todo```                   | Get all todo based on user logged in. ```params :  [page,limit,status] ``` . in status param you can fill value  ``` completed ```  or  ``` ongoing ``` | 200         |
| POST   | ```api/v1/todo/new```               | Create new todo                                                                                                                                         | 201         |
| GET    | ```api/v1/todo/{todoId}```          | Get detail of todo based on {todoId}                                                                                                                    | 200         |
| DELETE | ```api/v1/todo/{todoId}```          | Delete a todo based on {todoId}                                                                                                                         | 200         |
| PUT    | ```api/v1/todo/{todoId}```          | Update a specific todo based on {todoId}                                                                                                                | 200         |
| PUT    | ```api/v1/todo/{todoId}/complete``` | Complete a specific todo based on {todoId}                                                                                                              | 200         |


