<x-app-home-layout>

    <div class="relative flex min-h-screen sm:px-8 mt-9">
        <div class="mx-auto max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <div class="max-w-2xl  text-gray-700 dark:text-gray-500">
                        <h1 class="text-4xl text-center font-bold tracking-tight text-zinc-800 dark:text-zinc-100 sm:text-5xl">42 wWw</h1>
                        <p class="mt-6 text-base text-zinc-600 dark:text-zinc-400">Salut à toi, étudiant de 42.</p>
                        <p class="mt-6 text-base text-zinc-600 dark:text-zinc-400">Est-tu prêt à découvrir toute la richesse de ce site ?</p>
                        <div class="text-center mt-5">
                            <a href="{{ route('auth.redirect') }}" class="inline-block p-5 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Connexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
                                
</x-app-home-layout>