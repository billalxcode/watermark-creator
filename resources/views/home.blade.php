<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watermark Creator</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
<body>
    <div class="container my-3">
        <div class="row d-flex justify-content-center">
            <div class="col-8" id="alerts">
                <div class="alert alert-warning">Aplikasi dalam tahap pengembangan!</div>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center fw-bold">Watermark Creator</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('api.image.upload') }}" method="post" id="myform">
                            <div class="row">
                                <img src="" alt="" id="previewImage" class="col-12">
                                <div class="col-12 mb-3">
                                    <label for="formFile" class="form-label">Upload your image</label>
                                    <input class="form-control" type="file" id="formFile" name="image">
                                </div>
                                <div class="col-12 mb-3">
                                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-footer">
                        Copyright &copy; 2023 Billal Fauzan
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-8 table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Path</th>
                            <th>Dest</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js" integrity="sha512-8Z5++K1rB3U+USaLKG6oO8uWWBhdYsM3hmdirnOEWp8h2B1aOikj5zBzlXs8QOrvY9OxEnD2QDkbSKKpfqcIWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        $("#formFile").on("change", function () {
            let originalFile = $("#formFile").prop("files")[0]
            if (originalFile) {

                $("#previewImage").prop("src", URL.createObjectURL(originalFile))
            }
        })

        $("form").on("submit", function (e) {
            e.preventDefault()
            const originalFile = $("#formFile").prop("files")[0]
            const formData = new FormData(this)
            console.log(formData)
        
            $.ajax({
                method: "POST",
                url: $("form").prop("action"),
                data: formData,
                contentType: false,
                processData: false,
                enctype: "multipart/form-data",
                cache: false,
                success: function (response) {
                    if (response['status']) {
                        console.log(response)
                        $("#alerts").html(
                            `<div class="alert alert-success">Berhasil upload file</div>`
                        )
                    }
                    $("#previewImage").prop("src", "")
                    $("form").trigger("reset")
                },
                error: function (response) {
                    
                },
                complete: function (response) {
                    fetchData()
                }
            })
        })

        const fetchData = () => {
            const tableBody = $("#table-body")
            tableBody.html('')
            $.ajax({
                method: "POST",
                url: "{{ route('api.image.fetch') }}",
                success: function (response) {
                    if (response.data.length > 0) {
                        response.data.forEach((data) => {
                            tableBody.append(
                                `<tr>
                                    <td><img src="${data.url}" alt="Path" class="img-thumbnail" /></td>
                                    <td>${data.dest != null ? `<img src="${data.dest}" alt="Path" class="img-thumbnail" />` : 'NULL'}</td>
                                    <td>
                                        <span class="badge ${data.status == 'success' ? 'bg-success' : (data.status == 'processing' ? 'bg-info' : 'bg-danger')}">${data.status}</span>
                                    </td>
                                </tr>`
                            )
                        })
                    }
                },
                error: function () {

                }
            })
        }

        $(document).ready(function () {
            fetchData()
        })
    </script>
</body>
</html>