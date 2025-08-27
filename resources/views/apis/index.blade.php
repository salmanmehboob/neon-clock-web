@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <!-- Page header -->
        <div class="row g-3 align-items-center mb-4">
            <div class="col">
                <h1 class="h4 mb-1">API Playground</h1>
                <p class="text-muted mb-0">
                    Test your endpoints quickly. For now, all APIs are open — no authentication required.
                </p>
            </div>
            <div class="col-auto d-none">
                <!-- Hidden until auth is needed again -->
                <button id="btn-generate-token" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-key"></i> Create Admin Token
                </button>
                <span id="token-badge" class="ms-2 text-muted small"></span>
            </div>
        </div>

        <div class="row gy-3">
            <!-- Examples -->
            <div class="col-12 col-lg-3">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-header bg-transparent fw-semibold">
                        Examples
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="examples-list">
                            @foreach($examples as $ex)
                                <li class="list-group-item">
                                    <div class="small text-uppercase text-muted fw-semibold">{{ $ex['method'] }}</div>
                                    <a href="#" class="example-item d-inline-block mt-1"
                                       data-method="{{ $ex['method'] }}"
                                       data-endpoint="{{ $ex['endpoint'] }}">
                                        {{ $ex['title'] }}
                                    </a>
                                    <div class="text-muted small mt-1"><code>{{ $ex['endpoint'] }}</code></div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Request + Response -->
            <div class="col-12 col-lg-6">
                <!-- Request -->
                <form id="apiForm" onsubmit="return false;">
                    <div class="card shadow-sm border-0 rounded-3 mb-3">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Request</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                Open API (No Auth)
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Method</label>
                                    <select id="method" class="form-select">
                                        <option>GET</option>
                                        <option>POST</option>
                                        <option>PUT</option>
                                        <option>PATCH</option>
                                        <option>DELETE</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-8">
                                    <label class="form-label">Endpoint (relative or absolute)</label>
                                    <input id="endpoint" class="form-control" placeholder="/api/categories">
                                    <div class="form-text">Use absolute URL or relative path starting with /</div>
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info py-2 px-3 mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Auth is not required. Leave token empty.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Headers (JSON)</label>
                                    <textarea id="headers" rows="3" class="form-control" placeholder='{"Accept":"application/json"}'></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Body (JSON)</label>
                                    <textarea id="body" rows="6" class="form-control" placeholder='{"key":"value"}'></textarea>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button id="btn-call" class="btn btn-primary">
                                    <i class="bi bi-play-circle"></i> Call API
                                </button>
                                <button id="btn-clear" class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-eraser"></i> Clear Response
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Response -->
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-transparent fw-semibold">Response</div>
                    <div class="card-body p-4">
                        <div class="row g-2 mb-3">
                            <div class="col-12 col-md-7">
                                <div class="small text-muted">Called URL</div>
                                <div id="api-url" class="text-break"></div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="small text-muted">Status</div>
                                <div id="resp-status">-</div>
                            </div>
                            <div class="col-6 col-md-3 text-md-end">
                                <button type="button" id="copy-resp-body" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-clipboard"></i> Copy body
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted mb-1">Headers</div>
                            <pre id="resp-headers" class="small bg-light p-3 rounded border" style="max-height:200px; overflow:auto;"></pre>
                        </div>

                        <div>
                            <div class="small text-muted mb-1">Body</div>
                            <pre id="resp-body" class="bg-light p-3 rounded border mb-0" style="max-height:420px; overflow:auto; white-space: pre-wrap; word-break: break-word;"></pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="col-12 col-lg-3">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-header bg-transparent fw-semibold">Quick tips</div>
                    <div class="card-body small">
                        <ul class="mb-0">
                            <li>Use <code>/api/...</code> for internal API endpoints.</li>
                            <li>Set <code>Accept: application/json</code> in headers to get JSON responses.</li>
                            <li>Send JSON in the Body for POST/PUT/PATCH.</li>
                            <li>Auth is currently disabled here for ease of testing.</li>
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
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function pretty(data) {
        try { return JSON.stringify(typeof data === 'string' ? JSON.parse(data) : data, null, 2); }
        catch (_) { return typeof data === 'string' ? data : JSON.stringify(data, null, 2); }
    }

    document.getElementById('btn-call').addEventListener('click', async () => {
        const endpoint = document.getElementById('endpoint').value.trim();
        const method = document.getElementById('method').value.trim();
        const headersText = document.getElementById('headers').value.trim();
        const bodyText = document.getElementById('body').value.trim();

        let headersObj = {};
        if (headersText) {
            try { headersObj = JSON.parse(headersText); }
            catch (e) { alert('Headers must be valid JSON'); return; }
        }

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
                    // bearer_token intentionally omitted (open API)
                })
            });

            const data = await res.json();
            if (!res.ok) {
                document.getElementById('resp-status').textContent = `${res.status} Error`;
                document.getElementById('resp-headers').textContent = pretty(data.headers ?? data);
                document.getElementById('resp-body').textContent = pretty(data);
                document.getElementById('api-url').textContent = data.url ?? endpoint;
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

    // Examples quick-fill
    document.querySelectorAll('.example-item').forEach(el => {
        el.addEventListener('click', (ev) => {
            ev.preventDefault();
            document.getElementById('endpoint').value = el.dataset.endpoint || '';
            document.getElementById('method').value = el.dataset.method || 'GET';
            document.getElementById('headers').value = '{"Accept":"application/json"}';
            document.getElementById('body').value = '';
        });
    });

    // Clear response
    document.getElementById('btn-clear').addEventListener('click', () => {
        document.getElementById('resp-status').textContent = '-';
        document.getElementById('resp-headers').textContent = '';
        document.getElementById('resp-body').textContent = '';
        document.getElementById('api-url').textContent = '';
    });

    // Copy response body
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
            } catch (e) {}
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.setAttribute('readonly', '');
            ta.style.position = 'fixed';
            ta.style.top = '-9999px';
            document.body.appendChild(ta);
            ta.select();
            let ok = false;
            try { ok = document.execCommand('copy'); } catch (e) {}
            document.body.removeChild(ta);
            return ok;
        }

        btn.addEventListener('click', async () => {
            const text = (bodyEl.innerText || bodyEl.textContent || '').trim();
            if (!text) {
                btn.textContent = 'Nothing to copy';
                setTimeout(() => (btn.textContent = 'Copy body'), 1200);
                return;
            }
            const ok = await copyText(text);
            btn.textContent = ok ? 'Copied!' : 'Copy failed';
            setTimeout(() => (btn.textContent = 'Copy body'), 1200);
        });
    })();
</script>
@endpush
