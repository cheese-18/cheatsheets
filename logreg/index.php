<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <style>
        [v-cloak] { display: none; }
        :root { font-family: Arial, sans-serif; color: #202124; }
        body { background: #f8fafd; }
        .brand { font-family: Arial, sans-serif; letter-spacing: -0.08em; }
        .field { transition: border-color 160ms ease, box-shadow 160ms ease; }
        .field:focus { border-color: #1a73e8; box-shadow: 0 0 0 1px #1a73e8; outline: none; }
        .primary-button { transition: background 160ms ease, box-shadow 160ms ease; }
        .primary-button:hover:not(:disabled) { background: #1769d1; box-shadow: 0 1px 3px rgba(60, 64, 67, .24); }
    </style>
</head>
<body class="min-h-screen">
    <main id="app" v-cloak class="flex min-h-screen flex-col items-center px-5 py-10 sm:justify-center sm:py-12">
        <section class="w-full max-w-[450px] rounded-lg border border-[#dadce0] bg-white px-6 py-8 shadow-sm sm:px-10 sm:py-10">
            <div class="text-center">
                <div class="brand mb-5 text-[30px] font-medium" aria-label="Account">
                    <span class="text-[#4285f4]">A</span><span class="text-[#ea4335]">c</span><span class="text-[#fbbc05]">c</span><span class="text-[#4285f4]">o</span><span class="text-[#34a853]">u</span><span class="text-[#ea4335]">n</span><span class="text-[#4285f4]">t</span>
                </div>
                <h1 class="text-2xl font-normal tracking-tight">{{ isLogin ? 'Sign in' : 'Create your account' }}</h1>
                <p class="mt-2 text-base text-[#3c4043]">{{ isLogin ? 'to continue to your account' : 'Get started in just a few seconds' }}</p>
            </div>

            <div class="mt-8 flex rounded-md bg-[#f1f3f4] p-1" role="tablist" aria-label="Account action">
                <button type="button" @click="switchForm(true)" role="tab" :aria-selected="isLogin"
                    class="flex-1 rounded px-3 py-2 text-sm font-medium transition"
                    :class="isLogin ? 'bg-white text-[#1a73e8] shadow-sm' : 'text-[#5f6368]'">Sign in</button>
                <button type="button" @click="switchForm(false)" role="tab" :aria-selected="!isLogin"
                    class="flex-1 rounded px-3 py-2 text-sm font-medium transition"
                    :class="!isLogin ? 'bg-white text-[#1a73e8] shadow-sm' : 'text-[#5f6368]'">Create account</button>
            </div>

            <form @submit.prevent="submitForm" class="mt-7">
                <div v-if="!isLogin" class="mb-5">
                    <label for="name" class="mb-2 block text-sm font-medium text-[#3c4043]">Full name</label>
                    <input id="name" v-model.trim="form.name" type="text" autocomplete="name"
                        class="field w-full rounded border border-[#dadce0] px-3 py-3 text-base"
                        placeholder="Juan Dela Cruz" required>
                </div>

                <div class="mb-5">
                    <label for="email" class="mb-2 block text-sm font-medium text-[#3c4043]">Email</label>
                    <input id="email" v-model.trim="form.email" type="email" autocomplete="email"
                        class="field w-full rounded border border-[#dadce0] px-3 py-3 text-base"
                        placeholder="you@example.com" required>
                </div>

                <div class="mb-2">
                    <label for="password" class="mb-2 block text-sm font-medium text-[#3c4043]">Password</label>
                    <input id="password" v-model="form.password" type="password"
                        :autocomplete="isLogin ? 'current-password' : 'new-password'" minlength="6"
                        class="field w-full rounded border border-[#dadce0] px-3 py-3 text-base"
                        placeholder="Enter your password" required>
                </div>
                <p v-if="!isLogin" class="mb-5 text-xs text-[#5f6368]">Use 6 or more characters.</p>

                <p v-if="message" role="status" class="my-5 rounded bg-[#f8fafd] px-3 py-2 text-sm"
                    :class="success ? 'text-[#188038]' : 'text-[#d93025]'">
                    {{ message }}
                </p>

                <div class="mt-7 flex items-center justify-between gap-4">
                    <button type="button" @click="switchForm(!isLogin)" class="text-sm font-medium text-[#1a73e8] hover:underline">
                        {{ isLogin ? 'Create account' : 'Back to sign in' }}
                    </button>
                    <button type="submit" :disabled="loading"
                        class="primary-button rounded bg-[#1a73e8] px-5 py-2.5 text-sm font-medium text-white disabled:cursor-not-allowed disabled:opacity-50">
                        {{ loading ? 'Please wait...' : (isLogin ? 'Sign in' : 'Create account') }}
                    </button>
                </div>
            </form>
        </section>

        <footer class="mt-8 flex w-full max-w-[450px] items-center justify-between px-2 text-xs text-[#5f6368]">
            <span>Secure account access</span>
            <span>Privacy &amp; terms</span>
        </footer>
    </main>

    <script>
        const { createApp, reactive, ref } = Vue;

        createApp({
            setup() {
                const isLogin = ref(true);
                const loading = ref(false);
                const message = ref('');
                const success = ref(false);
                const form = reactive({ name: '', email: '', password: '' });

                function switchForm(showLogin) {
                    isLogin.value = showLogin;
                    message.value = '';
                    success.value = false;
                }

                async function submitForm() {
                    loading.value = true;
                    message.value = '';

                    try {
                        const endpoint = isLogin.value ? 'login.php' : 'register.php';
                        const response = await fetch(endpoint, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(form)
                        });
                        const data = await response.json();

                        success.value = data.success;
                        message.value = data.success && isLogin.value
                            ? `Welcome, ${data.name}!`
                            : data.message;

                        if (data.success && !isLogin.value) {
                            form.name = '';
                            form.email = '';
                            form.password = '';
                            isLogin.value = true;
                        }
                    } catch (error) {
                        success.value = false;
                        message.value = 'Something went wrong. Please try again.';
                    } finally {
                        loading.value = false;
                    }
                }

                return { isLogin, loading, message, success, form, switchForm, submitForm };
            }
        }).mount('#app');
    </script>
</body>
</html>
