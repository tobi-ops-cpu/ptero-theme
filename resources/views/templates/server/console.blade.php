@extends('layouts.app')

@section('title', 'Console')

@section('content')
<div class="flex gap-6">
    <!-- Sidebar with Controls -->
    <div class="w-48 space-y-4">
        <button class="w-full py-2 px-4 rounded-lg bg-green-600 hover:bg-green-700 transition transform hover:scale-105 shadow-lg text-white font-medium">
            Start
        </button>
        <button class="w-full py-2 px-4 rounded-lg bg-red-600 hover:bg-red-700 transition transform hover:scale-105 shadow-lg text-white font-medium">
            Stop
        </button>
        <button class="w-full py-2 px-4 rounded-lg bg-yellow-500 hover:bg-yellow-600 transition transform hover:scale-105 shadow-lg text-white font-medium">
            Restart
        </button>
    </div>

    <!-- Main Console Content -->
    <div class="flex-1 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Server Info Card -->
            <div class="bg-card rounded-xl p-6 shadow-lg border border-gray transition hover:shadow-xl hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4">{{ $server->name }}</h2>
                <div class="space-y-3 text-gray-300 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-green-400 animate-pulse"></span>
                        Online
                    </div>
                    <div>Uptime: {{ $server->uptime_formatted }}</div>
                    <div>IP: {{ $server->ip_address }}:{{ $server->port }}</div>
                    <div>ID: #{{ $server->identifier }}</div>
                    <div>Node: {{ $server->node->name }}</div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-card rounded-xl p-6 shadow-lg border border-gray transition hover:shadow-xl hover:scale-[1.02]">
                    <div class="text-2xl font-bold text-accent">{{ number_format($server->cpu_usage, 2) }}%</div>
                    <div class="text-xs text-gray-400">CPU Usage</div>
                </div>
                <div class="bg-card rounded-xl p-6 shadow-lg border border-gray transition hover:shadow-xl hover:scale-[1.02]">
                    <div class="text-2xl font-bold text-accent">{{ number_format($server->memory_usage / 1024, 2) }} GiB</div>
                    <div class="text-xs text-gray-400">RAM Used</div>
                </div>
            </div>
        </div>

        <!-- Console Output -->
        <div class="bg-card rounded-xl p-6 shadow-lg border border-gray">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Console Output</h3>
                <button class="text-xs px-3 py-1 rounded-md bg-gray-700 hover:bg-gray-600 transition text-gray-200">
                    Clear
                </button>
            </div>
            <div id="console-output" class="bg-black text-green-400 p-4 rounded-lg h-96 overflow-y-auto font-mono text-sm">
                <!-- Console logs go here -->
            </div>
        </div>
    </div>
</div>
@endsection
