@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>API Tester</h4>
            <div>
                <button id="btn-generate-token" class="btn btn-outline-secondary btn-sm">Create Admin Token</button>
                <span id="token-badge" class="ms-2 text-muted small"></span>
            </div>
        </div>

        <div class="row gy-3">
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header">Examples</div>
                    <div class="card-body p-2">
                        <ul class="list-group list-group-flush" id="examples-list">
                            @foreach($examples as $ex)
                                <li class="list-group-item">
                                    <div class="small text-muted">{{ $ex['method'] }}</div>
                                    <div>
                                        <a href="#" class="example-item" data-method="{{ $ex['method'] }}" data-endpoint="{{ $ex['endpoint'] }}">
                                            {{ $ex['title'] }}
                                        </a>
                                    </div>
                                    <div class="text-muted small mt-1">{{ $ex['endpoint'] }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <form id="apiForm" onsubmit="return false;">
                    <div class="card mb-3">
                        <div class="card-header">Request</div>
                        <div class="card-body">

                            <div class="mb-2">
                                <label class="form-label">Method</label>
                                <select id="method" class="form-select">
                                    <option>GET</option>
                                    <option>POST</option>
                                    <option>PUT</option>
                                    <option>PATCH</option>
                                    <option>DELETE</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Endpoint (relative or absolute)</label>
                                <input id="endpoint" class="form-control" placeholder="/api/categories">
                                <div class="form-text">You can use absolute URL or relative path (starting with /).</div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Auth (Bearer token)</label>
                                <div class="input-group">
                                    <input id="bearer_token" class="form-control" placeholder="Paste token or click Create Admin Token">
                                    <button id="use_token_btn" type="button" class="btn btn-outline-secondary">Use</button>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Headers (JSON)</label>
                                <textarea id="headers" rows="3" class="form-control" placeholder='{"Accept":"application/json"}'></textarea>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Body (JSON)</label>
                                <textarea id="body" rows="6" class="form-control" placeholder='{"key":"value"}'></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button id="btn-call" class="btn btn-primary">Call API</button>
                                <button id="btn-clear" class="btn btn-outline-secondary">Clear Response</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card">
                    <div class="card-header">Response</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Called URL:</strong> <span id="api-url"></span>
                        </div>
                         <div class="mb-2">
                            <strong>Status:</strong> <span id="resp-status">-</span>
                        </div>
                        <div class="mb-2">
                            <strong>Headers:</strong>
                            <pre id="resp-headers" class="small bg-light p-2" style="max-height:200px; overflow:auto;"></pre>
                        </div>



                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Response Body</h6>
                            <button type="button" id="copy-resp-body" class="btn btn-sm btn-outline-secondary">
                                Copy
                            </button>
                        </div>
                        <pre id="resp-body" class="mb-0" style="white-space: pre-wrap; word-break: break-word;"></pre>

                        
                        {{--                        <div>--}}
{{--                            <strong>Body:</strong>--}}
{{--                            <pre id="resp-body" class="small bg-light p-2  " style="max-height:400px; overflow:auto; white-space:pre-wrap;"></pre>--}}
{{--                        </div>--}}
                    </div>
                </div>

 
            </div>

            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header">Quick tips</div>
                    <div class="card-body small">
                        <ul>
                            <li>Use <code>/api/...</code> for internal API endpoints.</li>
                            <li>Create a token to test protected endpoints (Sanctum personal token).</li>
                            <li>Set <code>Accept: application/json</code> in headers for JSON responses.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const callUrl = "{{ route('admin.apis.call') }}";
        const tokenUrl = "{{ route('admin.apis.token') }}";
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // helper to pretty print JSON or raw
        function pretty(data) {
            try {
                return JSON.stringify(data, null, 2);
            } catch (e) {
                return String(data);
            }
        }

        document.getElementById('btn-call').addEventListener('click', async () => {
            const endpoint = document.getElementById('endpoint').value.trim();
            const method = document.getElementById('method').value.trim();
            const headersText = document.getElementById('headers').value.trim();
            const bodyText = document.getElementById('body').value.trim();
            const bearer = document.getElementById('bearer_token').value.trim();

            // parse headers JSON if present
            let headersObj = {};
            if (headersText) {
                try {
                    headersObj = JSON.parse(headersText);
                } catch (e) {
                    alert('Headers must be valid JSON');
                    return;
                }
            }

            // call server route to perform HTTP request
            try {
                document.getElementById('resp-body').textContent = "Loading...";
                const res = await fetch(callUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        endpoint,
                        method,
                        headers: headersObj,
                        body: bodyText,
                        bearer_token: bearer
                    })
                });

                const data = await res.json();
                if (!res.ok) {
                    document.getElementById('resp-status').textContent = `${res.status} Error`;
                    document.getElementById('resp-headers').textContent = pretty(data.headers ?? data);
                    document.getElementById('resp-body').textContent = pretty(data);
                    return;
                }

                document.getElementById('resp-status').textContent = data.status;
                document.getElementById('resp-headers').textContent = pretty(data.headers);
                document.getElementById('api-url').textContent = data.url ?? endpoint;

                document.getElementById('resp-body').textContent = pretty(data.body);
            } catch (err) {
                document.getElementById('resp-status').textContent = 'Request failed';
                document.getElementById('resp-body').textContent = String(err);
            }
        });

        // create token
        document.getElementById('btn-generate-token').addEventListener('click', async () => {
            if (!confirm('Create a new personal access token for your user? This will create a token stored in DB.')) return;
            try {
                const res = await fetch(tokenUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (res.ok && data.token) {
                    document.getElementById('token-badge').textContent = data.token;
                    document.getElementById('bearer_token').value = data.token;
                    alert('Token created and filled into the Bearer field.');
                } else {
                    alert('Token creation failed: ' + (data.message || JSON.stringify(data)));
                }
            } catch (e) {
                alert('Token creation request failed: ' + e.message);
            }
        });

        // example items fill form
        document.querySelectorAll('.example-item').forEach(el => {
            el.addEventListener('click', (ev) => {
                ev.preventDefault();
                const endpoint = el.dataset.endpoint;
                const method = el.dataset.method;
                document.getElementById('endpoint').value = endpoint;
                document.getElementById('method').value = method;
                document.getElementById('headers').value = '{"Accept":"application/json"}';
                document.getElementById('body').value = '';
            });
        });

        // clear response
        document.getElementById('btn-clear').addEventListener('click', () => {
            document.getElementById('resp-status').textContent = '-';
            document.getElementById('resp-headers').textContent = '';
            document.getElementById('resp-body').textContent = '';
        });
    </script>
<script>
// Copy response body to clipboard
(function () {
    const btn = document.getElementById('copy-resp-body');
    const bodyEl = document.getElementById('resp-body');

    if (!btn || !bodyEl) return;

    async function copyText(text) {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return true;
            }
        } catch (e) {
            // fall back to execCommand
        }

        const ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'fixed';
        ta.style.top = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try {
            const ok = document.execCommand('copy');
            document.body.removeChild(ta);
            return ok;
        } catch (e) {
            document.body.removeChild(ta);
            return false;
        }
    }

    btn.addEventListener('click', async () => {
        const text = (bodyEl.innerText || bodyEl.textContent || '').trim();
        if (!text) {
            btn.textContent = 'Nothing to copy';
            setTimeout(() => (btn.textContent = 'Copy'), 1200);
            return;
        }

        const ok = await copyText(text);
        btn.textContent = ok ? 'Copied!' : 'Copy failed';
        setTimeout(() => (btn.textContent = 'Copy'), 1200);
    });
})();
</script>
@endpush
