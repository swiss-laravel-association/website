<div>
    @if($subscribed)
        <p class="mt-2 text-green-500 dark:text-green-700 font-semibold">You have subscribed successfully. Please check your inbox.</p>
    @else
        <form wire:submit.prevent="submit"
              class="my-4 w-full max-w-md lg:col-span-5 lg:pt-2 space-y-3"
        >
            <div>
                <div class="flex gap-x-4">
                    <label for="name" class="sr-only">Name</label>
                    <input id="name" name="name" type="text" wire:model.live="name" autocomplete="name" required class="min-w-0 flex-auto rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 focus-visible:outline-none sm:text-sm/6" placeholder="Name">
                </div>
                <div class="mt-2 text-sm text-red-600">@error('name') {{ $message }} @enderror</div>
            </div>

            <div>
                <div class="flex gap-x-4">
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" wire:model="email" autocomplete="email" required class="min-w-0 flex-auto rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 focus-visible:outline-none sm:text-sm/6" placeholder="E-Mail">
                </div>
                <div class="mt-2 text-sm text-red-600">@error('email') {{ $message }} @enderror</div>
            </div>

            <button type="submit"
                    class="flex-none rounded-md bg-primary-500 dark:bg-gray-700 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 dark:hover:bg-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
            >
                Subscribe
            </button>
        </form>
    @endif
</div>
