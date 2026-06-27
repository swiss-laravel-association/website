<flux:header container
             class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 flex items-center">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    <flux:brand :href="route('home')"
                logo="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/logos/logo-mark-color-small.png') }}"
                name="Swiss Laravel Association" class="max-lg:hidden dark:hidden"/>
    <flux:brand :href="route('home')"
                logo="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/logos/logo-mark-color-small.png') }}"
                name="Swiss Laravel Association" class="max-lg:hidden! hidden dark:flex"/>

    <flux:spacer/>


    <flux:navbar class="me-4 lg:hidden">
        <flux:navbar.item :href="route('events.next-event')" icon:trailing="arrow-up-right">
            Next Event
        </flux:navbar.item>
    </flux:navbar>


    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item :href="route('events.index')"
                          :current="request()->routeIs(['events.index', 'events.show'])">
            Events
        </flux:navbar.item>
        <flux:navbar.item :href="route('association.sponsors')"
                          :current="request()->routeIs('association.sponsors')">
            Sponsors
        </flux:navbar.item>
        <flux:navbar.item :href="route('blog.index')"
                          :current="request()->routeIs('blog.index')">
            Blog
        </flux:navbar.item>

        @if (false)
            <flux:navbar.item href="#">Association</flux:navbar.item>

            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item icon:trailing="chevron-down">Association</flux:navbar.item>

                <flux:navmenu>
                    <flux:navmenu.item href="#">About</flux:navmenu.item>
                    <flux:navmenu.item href="#">Team</flux:navmenu.item>
                    <flux:navmenu.item href="#">Membership</flux:navmenu.item>
                    <flux:navmenu.item href="#">Become a member</flux:navmenu.item>
                    <flux:navmenu.separator/>
                    <flux:navmenu.item href="#">Sponsors</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>

        @endif
    </flux:navbar>
</flux:header>

<flux:sidebar sticky collapsible="mobile"
              class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.header>
        <flux:sidebar.brand
            :href="route('home')"
            :logo="\Illuminate\Support\Facades\Vite::asset('resources/images/logos/logo-mark-color-small.png') "
            :logo:dark="\Illuminate\Support\Facades\Vite::asset('resources/images/logos/logo-mark-color-small.png') "
            name="Swiss Laravel Association"
        />

        <flux:sidebar.collapse
            class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2"/>
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.item :href="route('events.index')"
                           :current="request()->routeIs(['events.index', 'events.show'])">
            Events
        </flux:sidebar.item>
        <flux:sidebar.item :href="route('association.sponsors')"
                           :current="request()->routeIs('association.sponsors')">Sponsors
        </flux:sidebar.item>

        @if (false)
            <flux:sidebar.group expandable heading="Favorites" class="grid">
                <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
            </flux:sidebar.group>
        @endif
    </flux:sidebar.nav>

    <flux:sidebar.spacer/>

    @if(false)
        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
        </flux:sidebar.nav>
    @endif
</flux:sidebar>
