<form id="updateForm" action="{{ route('app.update') }}" method="POST">
  @csrf
  <button type="submit" class="btn btn-warning">Update App</button>
</form>

<div class="mt-3">
  <div class="progress" style="height: 20px; display:none;">
    <div class="progress-bar progress-bar-striped progress-bar-animated" 
         role="progressbar" style="width: 0%"></div>
  </div>
  <ul id="updateLog" class="mt-2 list-unstyled"></ul>
</div>

<script>
const form = document.getElementById('updateForm');
const progress = document.querySelector('.progress');
const bar = document.querySelector('.progress-bar');
const log = document.getElementById('updateLog');

form.addEventListener('submit', async e => {
  e.preventDefault();
  if(!confirm('Update the app now?')) return;

  // reset UI
  log.innerHTML = '';
  progress.style.display = 'block';
  bar.style.width = '0%';
  bar.classList.remove('bg-success','bg-danger');

  // send request
  const res = await fetch(form.action, {
    method:'POST',
    headers:{'X-CSRF-TOKEN':'{{csrf_token()}}'}
  });
  const data = await res.json();

  const total = data.steps.length;
  data.steps.forEach((step, i) => {
    // progress increment
    bar.style.width = ((i+1)/total*100)+'%';
    // log output
    const li = document.createElement('li');
    li.innerHTML = `<strong>${step.step}</strong>: ${step.output}`;
    log.appendChild(li);
  });

  // final status
  if(data.status === 'success') {
    bar.classList.add('bg-success');
    bar.style.width = '100%';
    log.insertAdjacentHTML('beforeend','<li class="text-success fw-bold">✅ Update complete</li>');
  } else {
    bar.classList.add('bg-danger');
    log.insertAdjacentHTML('beforeend','<li class="text-danger fw-bold">❌ Update failed at step above</li>');
  }
});
</script>
