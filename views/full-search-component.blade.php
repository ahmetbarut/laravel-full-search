    <div class="modal fade mt-5" id="search" aria-hidden="true" aria-labelledby="searchModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search for..." id="search-input">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="list-group w-100" id="results" style="max-height: 400px; overflow-y:scroll">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('assets')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
        <script>
            (function() {
                const route = "{{ route('ahmetbarut.full-search') }}";

                var searchModal = new bootstrap.Modal(document.getElementById('search'), {
                    keyboard: true
                })

                document.addEventListener('keydown', function(e) {

                    if (e.keyCode === 75 && e.metaKey) {
                        searchModal.toggle();
                        e.preventDefault();
                    }
                });

                document.getElementById('search').addEventListener('shown.bs.modal', function(event) {
                    searchInput.focus();
                })

                var searchInput = document.getElementById('search-input');
                var results = document.getElementById('results');
                searchInput.addEventListener('keyup', function(e) {
                    var searchValue = e.target.value;

                    if (searchValue.length >= 3) {
                        fetch(`${route}?q=` + searchValue)
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                console.log(data);
                                results.innerHTML = '';
                                data.results.map(function(item) {
                                    var a = document.createElement('a');
                                    a.classList.add('list-group-item');
                                    a.classList.add('list-group-item-action');
                                    a.href = item.url;

                                    a.innerHTML = `
                            <h6>${item.title}</h6>
                            ${item.page !== null ? '<small>'+ item.page +'</small>' : ''}
                            `;
                                    results.append(a);
                                });
                            });
                    }

                    if (searchValue.length < 3) {
                        results.innerHTML = '';
                    }
                });
            })()
        </script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <style>
            #result-list li a:hover>h6 {
                color: white;
                text-decoration: none;
            }
        </style>
    @endpush
