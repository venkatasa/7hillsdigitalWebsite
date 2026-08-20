{{--
    POPIA requires that we tell a person what we collect and why AT THE POINT
    OF COLLECTION. A consent tick and a link to the privacy policy are not
    enough on their own, so this notice renders ABOVE the consent checkbox on
    every public form that takes personal information.
--}}
<div {{ $attributes->merge(['class' => 'rounded-lg bg-brand-light p-4 text-xs leading-relaxed text-secondary']) }}>
    <p class="font-semibold text-brand">How we use these details</p>
    <p class="mt-1.5">
        We collect your name, contact details and whatever you tell us about your
        business for one purpose: to answer this enquiry and, if you go ahead, to
        set up your website and email. We do not sell your details and we do not
        add you to a mailing list without asking. We keep the enquiry while it is
        live and for three years afterwards, then delete it.
    </p>
    <p class="mt-1.5">
        You can ask to see, correct or delete what we hold at any time by emailing
        <a href="mailto:{{ config('brand.contact.email') }}" class="font-medium text-brand underline underline-offset-2">{{ config('brand.contact.email') }}</a>.
        Full detail is in our <a href="{{ route('privacy') }}" class="font-medium text-brand underline underline-offset-2">privacy notice</a>.
    </p>
</div>
