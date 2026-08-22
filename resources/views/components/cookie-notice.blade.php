{{--
    Cookie notice.

    This site sets only strictly necessary cookies, which under POPIA and the
    ECT Act do not require consent — so this is a NOTICE, not a consent gate.
    There is deliberately no "reject" button: offering one that cannot do
    anything (the session and CSRF cookies are what make the site work) would be
    theatre, and it teaches people to dismiss the consent that does matter — the
    POPIA tick on the enquiry form.
--}}
<div x-data
     x-show="$store.cookieNotice.open"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-full opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-end="translate-y-full opacity-0"
     class="fixed inset-x-0 bottom-0 z-50 border-t border-white/10 bg-brand-dark/95 backdrop-blur print:hidden"
     role="region"
     aria-label="Cookie notice">
    <div class="container-page flex flex-col gap-4 py-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="max-w-3xl text-xs leading-relaxed text-white/75 sm:pr-4">
            This site sets <strong class="font-semibold text-white">two strictly necessary cookies</strong>
            — one to keep your session, one to stop forms being forged. No analytics, no advertising,
            and nothing loaded from anybody else's servers, so your visit is not disclosed to a third
            party. Read our
            <a href="{{ route('privacy') }}" class="font-semibold text-mint underline underline-offset-2 hover:brightness-110">privacy notice</a>.
        </p>

        <button type="button"
                x-on:click="$store.cookieNotice.dismiss()"
                class="btn-mint shrink-0 self-start px-6 py-2.5 text-sm sm:self-auto">
            Got it
        </button>
    </div>
</div>
