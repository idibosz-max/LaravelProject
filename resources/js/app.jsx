import React from 'react';
import { createRoot } from 'react-dom/client';
import ProductFilter from './components/ProductFilter';
import CartPreview from './components/CartPreview';
import StripeCheckout from './components/StripeCheckout';
import CartIcon from './components/CartIcon';

// ── Mount ProductFilter on homepage / shop ──────────────────────────────────
const productFilterRoot = document.getElementById('product-filter-root');
if (productFilterRoot) {
    const initial    = JSON.parse(productFilterRoot.dataset.initial    || '[]');
    const categories = JSON.parse(productFilterRoot.dataset.categories || '[]');
    createRoot(productFilterRoot).render(
        <ProductFilter initialProducts={initial} categories={categories} />
    );
}

// ── Cart icon with count badge ───────────────────────────────────────────────
const cartIconRoot = document.getElementById('cart-icon-root');
if (cartIconRoot) {
    createRoot(cartIconRoot).render(<CartIcon />);
}

// ── Cart preview drawer ──────────────────────────────────────────────────────
const cartPreviewRoot = document.getElementById('cart-preview-root');
if (cartPreviewRoot) {
    createRoot(cartPreviewRoot).render(<CartPreview />);
}

// ── Stripe checkout form ─────────────────────────────────────────────────────
const stripeRoot = document.getElementById('stripe-checkout-root');
if (stripeRoot) {
    const { total, stripeKey, action, csrf } = stripeRoot.dataset;
    createRoot(stripeRoot).render(
        <StripeCheckout
            total={parseFloat(total)}
            stripeKey={stripeKey}
            action={action}
            csrf={csrf}
        />
    );
}
