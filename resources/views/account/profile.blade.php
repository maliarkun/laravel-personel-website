@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', __('account.title'))

@section('content')
    <div class="grid gap-10 lg:grid-cols-2">
        <div class="rounded-3xl border border-slate-800/60 bg-slate-900/40 p-8 shadow-lg shadow-slate-950/40">
            <div class="mb-6">
                <h2 class="text-xl font-semibold uppercase tracking-[0.3em] text-amber-300">{{ __('account.profile_heading') }}</h2>
                <p class="mt-2 text-sm text-slate-400">{{ __('account.profile_description') }}</p>
            </div>

            <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.avatar') }}</label>
                    <div class="mt-2 flex items-center gap-4">
                        <div class="h-16 w-16 overflow-hidden rounded-full border border-slate-700/70 bg-slate-800">
                            @if(auth()->user()->avatar_path)
                                <img src="{{ Storage::url(auth()->user()->avatar_path) }}" alt="avatar" class="h-full w-full object-cover" />
                            @else
                                <div class="flex h-full w-full items-center justify-center text-xs text-slate-500">CN</div>
                            @endif
                        </div>
                        <input type="file" name="avatar" accept="image/*" class="text-sm text-slate-300">
                    </div>
                    @error('avatar')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.name') }}</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="mt-2 w-full rounded-xl border border-slate-800/80 bg-slate-950/70 px-4 py-3 text-sm focus:border-amber-400 focus:outline-none" required>
                    @error('name')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.username') }}</label>
                    <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}" class="mt-2 w-full rounded-xl border border-slate-800/80 bg-slate-950/70 px-4 py-3 text-sm focus:border-amber-400 focus:outline-none">
                    @error('username')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.bio') }}</label>
                    <textarea name="bio" rows="4" class="mt-2 w-full rounded-xl border border-slate-800/80 bg-slate-950/70 px-4 py-3 text-sm focus:border-amber-400 focus:outline-none">{{ old('bio', auth()->user()->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.timezone') }}</label>
                    <select name="timezone" class="mt-2 w-full rounded-xl border border-slate-800/80 bg-slate-950/70 px-4 py-3 text-sm focus:border-amber-400 focus:outline-none">
                        <option value="">{{ __('account.select_timezone') }}</option>
                        @foreach(timezone_identifiers_list() as $timezone)
                            <option value="{{ $timezone }}" @selected(old('timezone', auth()->user()->timezone) === $timezone)>{{ $timezone }}</option>
                        @endforeach
                    </select>
                    @error('timezone')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full rounded-full bg-amber-400/90 px-6 py-3 text-sm font-semibold uppercase tracking-[0.3em] text-slate-900 transition hover:bg-amber-300">
                    {{ __('account.save_profile') }}
                </button>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-800/60 bg-slate-900/40 p-8 shadow-lg shadow-slate-950/40">
            <div class="mb-6">
                <h2 class="text-xl font-semibold uppercase tracking-[0.3em] text-amber-300">{{ __('account.password_heading') }}</h2>
                <p class="mt-2 text-sm text-slate-400">{{ __('account.password_description') }}</p>
            </div>

            <form method="POST" action="{{ route('account.password.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.current_password') }}</label>
                    <input type="password" name="current_password" class="mt-2 w-full rounded-xl border border-slate-800/80 bg-slate-950/70 px-4 py-3 text-sm focus:border-amber-400 focus:outline-none" required>
                    @error('current_password')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.new_password') }}</label>
                    <input type="password" name="password" class="mt-2 w-full rounded-xl border border-slate-800/80 bg-slate-950/70 px-4 py-3 text-sm focus:border-amber-400 focus:outline-none" required>
                    @error('password')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" class="mt-2 w-full rounded-xl border border-slate-800/80 bg-slate-950/70 px-4 py-3 text-sm focus:border-amber-400 focus:outline-none" required>
                </div>

                <button type="submit" class="w-full rounded-full bg-slate-800 px-6 py-3 text-sm font-semibold uppercase tracking-[0.3em] text-amber-200 transition hover:bg-slate-700">
                    {{ __('account.save_password') }}
                </button>
            </form>
        </div>
    </div>
@endsection
