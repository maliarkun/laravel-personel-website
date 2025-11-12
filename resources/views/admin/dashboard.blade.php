@extends('layouts.admin')

@section('admin-content')
<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    <x-admin.stat-card :label="__('admin.categories')" :value="$categoryCount" />
    <x-admin.stat-card :label="__('admin.projects')" :value="$projectCount" />
    <x-admin.stat-card :label="__('admin.notes')" :value="$noteCount" />
    <x-admin.stat-card :label="__('admin.users')" :value="$userCount" />
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <x-admin.recent-list :title="__('admin.recent_projects')" :items="$recentProjects" item-title="title" />
    <x-admin.recent-list :title="__('admin.recent_notes')" :items="$recentNotes" item-title="title" />
</div>
@endsection
