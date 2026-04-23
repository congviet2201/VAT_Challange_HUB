{{-- File purpose: resources/views/goals/show.blade.php --}}

@extends('shop.layout.app')

@section('content')
    <div class="container mt-4">
        <div id="goal-feedback" class="mb-3"></div>
        <h2>{{ $goal->title }}</h2>
        <p>{{ $goal->description }}</p>
        <p><strong>Status:</strong> {{ $goal->status === 'completed' ? 'Hoàn thành' : 'Đang tiến hành' }}</p>
        <p>
            <strong>Tiến độ:</strong>
            <span id="goal-progress-count">{{ $goal->subGoals->where('status', 'completed')->count() }}</span>/{{ $goal->subGoals->count() }} mục tiêu phụ
        </p>

        <h3>Mục tiêu phụ</h3>
        @php
            $hasCompletedToday = $goal->last_completed_date === now()->toDateString();
        @endphp
        @if($hasCompletedToday && $goal->status !== 'completed')
            <div class="alert alert-warning mt-3">
                Bạn đã hoàn thành mục tiêu của ngày hôm nay. Hãy quay lại vào ngày mai.
            </div>
        @endif
        <div class="row">
            @foreach ($subGoals as $subGoal)
                <div class="col-md-6 mb-3">
                    <div class="card {{ $subGoal->status === 'completed' ? 'border-success' : 'border-warning' }}">
                        <div class="card-body">
                            <h5 class="card-title">Day {{ $subGoal->day }}: {{ $subGoal->title }}</h5>
                            <p class="card-text">{{ $subGoal->description }}</p>
                            <p><strong>Status:</strong> <span class="sub-goal-status">{{ $subGoal->status === 'completed' ? 'Hoàn thành' : 'Đang chờ' }}</span>
                            </p>

                            @if ($subGoal->status !== 'completed')
                                <h6>Proofs:</h6>
                                @if ($subGoal->proofs->isNotEmpty())
                                    <ul class="proof-list">
                                        @foreach ($subGoal->proofs as $proof)
                                            <li>{{ $proof->type }}: {{ $proof->content }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted empty-proof-text">Chưa có proof</p>
                                @endif

                                <form class="submit-proof-form" data-sub-goal-id="{{ $subGoal->id }}">
                                    <div class="mb-2">
                                        <select name="type" class="form-select" required>
                                            <option value="">Chọn loại proof</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image URL</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <textarea name="content" class="form-control" placeholder="Nội dung proof" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary btn-sm">Submit Proof</button>
                                </form>

                                @if($hasCompletedToday)
                                    <button class="btn btn-success btn-sm complete-btn" disabled>Hoàn thành</button>
                                @elseif($subGoal->proofs->isEmpty())
                                    <button class="btn btn-secondary btn-sm" disabled title="Vui lòng submit proof trước">Hoàn thành (Cần nộp proof)</button>
                                @else
                                    <button class="btn btn-success btn-sm complete-btn"
                                        data-sub-goal-id="{{ $subGoal->id }}">Hoàn thành</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <a href="{{ route('goals.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        <a href="{{ route('goals.create') }}" class="btn btn-primary">Tạo mục tiêu mới</a>
    </div>

    <script>
        (function () {
            var csrfToken = '{{ csrf_token() }}';
            var feedbackBox = document.getElementById('goal-feedback');

            function parseJsonSafe(text) {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    return null;
                }
            }

            function renderFeedback(type, message) {
                if (!feedbackBox) return;
                feedbackBox.innerHTML = '<div class="alert alert-' + type + '" role="alert">' + message + '</div>';
            }

            function buildErrorMessage(payload) {
                if (!payload) return 'Có lỗi xảy ra, vui lòng thử lại.';
                if (payload.message) return payload.message;
                if (payload.errors) {
                    var messages = [];
                    Object.keys(payload.errors).forEach(function (key) {
                        if (Array.isArray(payload.errors[key])) {
                            messages = messages.concat(payload.errors[key]);
                        }
                    });
                    if (messages.length) return messages.join(' ');
                }
                return 'Có lỗi xảy ra, vui lòng thử lại.';
            }

            function postForm(url, data, onSuccess, onError, onFinally) {
                var body = new URLSearchParams(data).toString();

                var xhr = new XMLHttpRequest();
                xhr.open('POST', url, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.onreadystatechange = function () {
                    if (xhr.readyState !== 4) return;

                    var payload = parseJsonSafe(xhr.responseText);

                    if (xhr.status >= 200 && xhr.status < 300) {
                        onSuccess(payload || {});
                    } else {
                        onError(payload || { message: 'Có lỗi xảy ra, vui lòng thử lại.' });
                    }

                    if (typeof onFinally === 'function') {
                        onFinally();
                    }
                };

                xhr.onerror = function () {
                    onError({ message: 'Không thể kết nối máy chủ. Vui lòng thử lại.' });
                    if (typeof onFinally === 'function') {
                        onFinally();
                    }
                };

                xhr.send(body);
            }

            document.addEventListener('submit', function (e) {
                var form = e.target;
                if (!form.classList.contains('submit-proof-form')) return;
                e.preventDefault();

                var subGoalId = form.getAttribute('data-sub-goal-id');
                var typeInput = form.querySelector('select[name="type"]');
                var contentInput = form.querySelector('textarea[name="content"]');
                var submitBtn = form.querySelector('button[type="submit"]');
                var cardBody = form.closest('.card-body');

                if (!subGoalId || !typeInput || !contentInput || !submitBtn || !cardBody) {
                    renderFeedback('danger', 'Không thể submit proof do thiếu dữ liệu biểu mẫu.');
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.textContent = 'Đang gửi...';

                postForm(
                    '/api/sub-goals/' + subGoalId + '/proof',
                    {
                        type: typeInput.value,
                        content: contentInput.value,
                        _token: csrfToken
                    },
                    function (response) {
                        renderFeedback('success', response.message || 'Submit proof thành công.');

                        var emptyText = cardBody.querySelector('.empty-proof-text');
                        var proofList = cardBody.querySelector('.proof-list');
                        if (!proofList) {
                            proofList = document.createElement('ul');
                            proofList.className = 'proof-list';
                            if (emptyText) emptyText.remove();
                            form.parentNode.insertBefore(proofList, form);
                        }

                        var proofType = response.proof && response.proof.type ? response.proof.type : typeInput.value;
                        var proofContent = response.proof && response.proof.content ? response.proof.content : contentInput.value;
                        var li = document.createElement('li');
                        li.textContent = proofType + ': ' + proofContent;
                        proofList.appendChild(li);

                        form.reset();

                        var completeBtn = cardBody.querySelector('.complete-btn');
                        if (!completeBtn) {
                            var disabledBtn = cardBody.querySelector('button.btn-secondary[title]');
                            if (disabledBtn) {
                                var newButton = document.createElement('button');
                                newButton.className = 'btn btn-success btn-sm complete-btn';
                                newButton.setAttribute('data-sub-goal-id', subGoalId);
                                newButton.textContent = 'Hoàn thành';
                                disabledBtn.replaceWith(newButton);
                            }
                        } else {
                            completeBtn.disabled = false;
                            completeBtn.classList.remove('btn-secondary');
                            completeBtn.classList.add('btn-success');
                        }
                    },
                    function (errorPayload) {
                        renderFeedback('danger', buildErrorMessage(errorPayload));
                    },
                    function () {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit Proof';
                    }
                );
            });

            document.addEventListener('click', function (e) {
                var target = e.target;
                if (!target.classList.contains('complete-btn')) return;

                var subGoalId = target.getAttribute('data-sub-goal-id');
                var cardBody = target.closest('.card-body');
                if (!subGoalId || !cardBody) {
                    renderFeedback('danger', 'Không thể hoàn thành mục tiêu phụ do thiếu dữ liệu.');
                    return;
                }

                target.disabled = true;
                target.textContent = 'Đang hoàn thành...';

                postForm(
                    '/api/sub-goals/' + subGoalId + '/complete',
                    { _token: csrfToken },
                    function (response) {
                        renderFeedback('success', response.message || 'Hoàn thành mục tiêu phụ thành công.');

                        var statusEl = cardBody.querySelector('.sub-goal-status');
                        if (statusEl) statusEl.textContent = 'Hoàn thành';

                        var form = cardBody.querySelector('.submit-proof-form');
                        if (form) form.remove();
                        target.remove();

                        var progressEl = document.getElementById('goal-progress-count');
                        if (progressEl) {
                            var currentCount = parseInt(progressEl.textContent, 10) || 0;
                            if (response.goal && typeof response.goal.completed_sub_goals === 'number') {
                                progressEl.textContent = String(response.goal.completed_sub_goals);
                            } else {
                                progressEl.textContent = String(currentCount + 1);
                            }
                        }
                    },
                    function (errorPayload) {
                        renderFeedback('danger', buildErrorMessage(errorPayload));
                        target.disabled = false;
                        target.textContent = 'Hoàn thành';
                    }
                );
            });
        })();
    </script>
@endsection
