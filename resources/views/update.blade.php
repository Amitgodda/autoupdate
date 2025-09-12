<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update App</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4">Application Updater</h1>

    <button id="startUpdate" class="btn btn-warning">
        <i class="bi bi-arrow-repeat"></i> Update App
    </button>

    <!-- Progress bar -->
    <div class="progress mt-4" style="height: 25px; display:none;">
        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div>
    </div>

    <!-- Output log -->
    <pre id="updateLog" class="mt-4 p-3 bg-white border rounded" style="max-height:400px; overflow:auto;"></pre>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const btn = document.getElementById('startUpdate');
const progress = document.querySelector('.progress');
const bar = document.querySelector('.progress-bar');
const log = document.getElementById('updateLog');

btn.addEventListener('click', async () => {
    log.textContent = '';
    progress.style.display = 'block';
    bar.classList.remove('bg-success','bg-danger');
    bar.style.width = '30%';
    bar.textContent = 'Updating…';

    // call your route that runs Artisan command
    const res = await fetch('{{ route('app.update') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    const data = await res.json();

    // fill log and finish bar
    log.innerHTML = data.output;
    bar.style.width = '100%';
    if (data.status === 'success') {
        bar.classList.add('bg-success');
        bar.textContent = 'Update Completed';
    } else {
        bar.classList.add('bg-danger');
        bar.textContent = 'Update Failed';
    }
});
</script>
</body>
</html>
