<div x-data="{ 
    darkMode: localStorage.getItem('darkMode') === 'true',
    toggle() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" x-init="
    if (darkMode) {
        document.documentElement.classList.add('dark');
    }
">
    <button @click="toggle" class="p-2 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors">
        <template x-if="!darkMode">
            <i data-lucide="moon" class="w-5 h-5"></i>
        </template>
        <template x-if="darkMode">
            <i data-lucide="sun" class="w-5 h-5 text-yellow-400"></i>
        </template>
    </button>
</div>
