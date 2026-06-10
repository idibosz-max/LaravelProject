import React, { useState } from 'react';
import { loadStripe } from '@stripe/stripe-js';
import { Elements, CardElement, useStripe, useElements } from '@stripe/react-stripe-js';

/**
 * StripeCheckout – React component wrapping Stripe.js for payment.
 * Week 9: Checkout & Order Management milestone.
 */
function CheckoutForm({ total, action, csrf }) {
    const stripe   = useStripe();
    const elements = useElements();
    const [loading, setLoading]     = useState(false);
    const [error, setError]         = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!stripe || !elements) return;

        setLoading(true);
        setError('');

        const card = elements.getElement(CardElement);

        // For sandbox: create payment method
        const { error: stripeError, paymentMethod } = await stripe.createPaymentMethod({
            type: 'card',
            card,
        });

        if (stripeError) {
            setError(stripeError.message);
            setLoading(false);
            return;
        }

        // Submit to Laravel
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = action;

        const csrfInput = document.createElement('input');
        csrfInput.type  = 'hidden';
        csrfInput.name  = '_token';
        csrfInput.value = csrf;
        form.appendChild(csrfInput);

        const piInput = document.createElement('input');
        piInput.type  = 'hidden';
        piInput.name  = 'payment_intent';
        piInput.value = paymentMethod.id;
        form.appendChild(piInput);

        document.body.appendChild(form);
        form.submit();
    };

    const cardStyle = {
        style: {
            base: {
                color: '#ffffff',
                fontFamily: '"DM Sans", sans-serif',
                fontSize:   '14px',
                '::placeholder': { color: '#4b5563' },
            },
            invalid: { color: '#DC2626' },
        },
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-6">
            <div>
                <label className="block text-xs text-gray-400 uppercase tracking-wider mb-2">Card Details</label>
                <div className="bg-[#111] border border-white/10 rounded px-4 py-4 focus-within:border-[#DC2626] transition-colors">
                    <CardElement options={cardStyle} />
                </div>
            </div>

            {error && (
                <div className="bg-red-900/30 border border-[#DC2626]/30 text-red-300 text-sm px-4 py-3 rounded">
                    {error}
                </div>
            )}

            <div className="bg-[#1a1a1a] border border-white/5 rounded p-4 text-xs text-gray-500">
                <p className="font-semibold text-gray-400 mb-1">Sandbox Test Cards:</p>
                <p>Success: <span className="text-white font-mono">4242 4242 4242 4242</span></p>
                <p>Declined: <span className="text-white font-mono">4000 0000 0000 0002</span></p>
                <p className="mt-1 text-gray-600">Any future date · Any CVC</p>
            </div>

            <button
                type="submit"
                disabled={!stripe || loading}
                className="w-full bg-[#DC2626] hover:bg-[#991B1B] disabled:bg-gray-700 disabled:text-gray-500 text-white font-semibold py-4 rounded uppercase tracking-widest text-sm transition-colors flex items-center justify-center gap-2"
            >
                {loading ? (
                    <>
                        <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        Processing…
                    </>
                ) : (
                    `Pay $${total.toFixed(2)}`
                )}
            </button>
        </form>
    );
}

export default function StripeCheckout({ total, stripeKey, action, csrf }) {
    const stripePromise = loadStripe(stripeKey);

    return (
        <Elements stripe={stripePromise}>
            <CheckoutForm total={total} action={action} csrf={csrf} />
        </Elements>
    );
}
