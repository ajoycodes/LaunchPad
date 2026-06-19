(function () {
    'use strict';

    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.upvote-btn');
        if (!btn) return;

        // Redirect guests to login
        if (btn.dataset.auth === 'false') {
            window.location.href = '/login';
            return;
        }

        const productId = btn.dataset.productId;
        if (!productId || btn.disabled) return;

        btn.disabled = true;
        btn.classList.add('loading');

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
            // Update all upvote buttons for this product on the page
            document.querySelectorAll(`.upvote-btn[data-product-id="${productId}"]`).forEach(b => {
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

                // Update the label on the show page (has the "upvotes" text node)
                const labelEl = b.querySelectorAll('span')[1];
                if (labelEl) {
                    labelEl.textContent = data.count === 1 ? 'upvote' : 'upvotes';
                }
            });
        })
        .catch(() => {
            // Silently restore on network error
        })
        .finally(() => {
            btn.disabled = false;
            btn.classList.remove('loading');
        });
    });
}());
