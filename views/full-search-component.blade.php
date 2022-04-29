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
    @push('scripts')
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
                        fetch('/test?q=' + searchValue)
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
    @endpush

    @push('styles')
        <style>
            #result-list li a:hover>h6 {
                color: white;
                text-decoration: none;
            }

        </style>
    @endpush
