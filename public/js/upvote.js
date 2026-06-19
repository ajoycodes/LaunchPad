(function () {
    'use strict';

    const CSRF      = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const inflight  = new Set(); // track product IDs with pending requests

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.upvote-btn');
        if (!btn) return;

        // Redirect guests to login
        if (btn.dataset.auth === 'false') {
            window.location.href = '/login';
            return;
        }

        const productId = btn.dataset.productId;
        if (!productId || inflight.has(productId)) return;

        inflight.add(productId);

        // Disable every button for this product while request is in flight
        const allBtns = document.querySelectorAll(`.upvote-btn[data-product-id="${productId}"]`);
        allBtns.forEach(b => { b.disabled = true; b.classList.add('loading'); });

        fetch(`/upvote/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept':       'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(res => {
            if (!res.ok) throw new Error(res.status);
            return res.json();
        })
        .then(data => {
            allBtns.forEach(b => {
                b.classList.toggle('upvoted', data.upvoted);
                b.setAttribute('aria-pressed', data.upvoted ? 'true' : 'false');

                const countEl = b.querySelector('.upvote-count');
                if (countEl) {
                    countEl.textContent = data.count;
                    countEl.classList.add('upvote-count--bump');
                    countEl.addEventListener('animationend', () => {
                        countEl.classList.remove('upvote-count--bump');
                    }, { once: true });
                }

                // Update plural label on show page
                const labelEl = b.querySelectorAll('span')[1];
                if (labelEl && !labelEl.classList.contains('upvote-count')) {
                    labelEl.textContent = data.count === 1 ? 'upvote' : 'upvotes';
                }
            });
        })
        .catch(() => {
            // Silently ignore — button state restored in finally
        })
        .finally(() => {
            inflight.delete(productId);
            allBtns.forEach(b => { b.disabled = false; b.classList.remove('loading'); });
        });
    });
}());
