<section class="rounded-2xl border border-emerald-100/90 bg-white/85 p-5 shadow-sm ring-1 ring-emerald-900/[0.03] sm:p-6" aria-labelledby="registration-password-heading">
    <h2 id="registration-password-heading" class="mb-3 flex items-center gap-3 text-base font-extrabold text-brand-dark">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-brand-primary" aria-hidden="true"><i class="fas fa-lock text-sm"></i></span>
        Sign-in password
    </h2>
    <p id="registration-password-help" class="mb-5 text-sm leading-relaxed text-brand-medium">Choose a password only you know. You will use it with your email to log in.</p>
    <div class="grid gap-5 sm:grid-cols-2">
        <div class="space-y-1.5">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-brand-dark">Password <span class="text-red-600" aria-hidden="true">*</span></label>
            <div class="reg-auth-password-wrap mt-1">
                <input id="password" type="password" name="password" required autocomplete="new-password" aria-required="true" aria-describedby="registration-password-help" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                    class="{{ $fieldClass }} min-h-[3rem] py-3 pl-4 pr-12"
                    placeholder="Create a strong password">
                <button type="button" class="reg-auth-password-toggle" aria-label="Show password" aria-pressed="false" data-password-toggle="password">
                    <i class="fas fa-eye text-base" aria-hidden="true"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 flex items-start gap-1.5 text-xs font-semibold text-red-700" role="alert"><i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i><span>{{ $message }}</span></p>
            @enderror
        </div>
        <div class="space-y-1.5">
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-brand-dark">Confirm password <span class="text-red-600" aria-hidden="true">*</span></label>
            <div class="reg-auth-password-wrap mt-1">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" aria-required="true" aria-describedby="registration-password-help" aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                    class="{{ $fieldClass }} min-h-[3rem] py-3 pl-4 pr-12"
                    placeholder="Same password again">
                <button type="button" class="reg-auth-password-toggle" aria-label="Show confirm password" aria-pressed="false" data-password-toggle="password_confirmation">
                    <i class="fas fa-eye text-base" aria-hidden="true"></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1 flex items-start gap-1.5 text-xs font-semibold text-red-700" role="alert"><i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i><span>{{ $message }}</span></p>
            @enderror
        </div>
    </div>
</section>
