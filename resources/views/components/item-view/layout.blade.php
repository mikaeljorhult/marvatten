<article
    class="py-6"
    x-data="{ tab: '{{ $this->activeTab }}' }"
    x-init="
        $watch('tab', value => {
            var url = new URL(window.location.origin + window.location.pathname);
            url.searchParams.append('tab', value);
            window.history.replaceState(null, null, url.toString());
        });
    "
>
    <x-item-view.header :tabs="$this->tabs" :activeTab="$this->activeTab" />

    {{ $slot }}
</article>
