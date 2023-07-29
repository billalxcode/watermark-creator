# Intro
The task of the ForIT company Asta Solusindo, where the task is to make a rest api to add a watermark using Laravel.

# How to install
```shell
git clone https://github.com/billalxcode/watermark-creator
cd watermark-creator
composer i
cp .env.example .env
```
Configure dotenv in .env file, change `QUEUE_CONNECTION` to `database` then adjust database configuration

```shell
php artisan key:generate
php artisan queue:table
php artisan migrate
```

# Docs
## Upload image
You can upload images
### Request
```http
POST /api/image/upload
```
| Parameter | Type | Description |
| :--- | :--- | :--- |
| `image` | `file` | **Required**. Your image here |

### Responses
```javascript
{
    "status": boolean,
    "data": {
        "path": string,
        "status": string,
        "dest": string | null,
        "updated_at": datetime,
        "created_at": datetime,
        "id": id
    }
}
```

### Example response
```json
{
    "status": true,
    "data": {
        "id": 7,
        "path": "images/1690591417_Screenshot (6).png",
        "status": "processing",
        "dest": null,
        "updated_at": "2023-07-29T00:43:37.000000Z",
        "created_at": "2023-07-29T00:43:37.000000Z"
    }
}
```