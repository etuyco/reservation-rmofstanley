@extends('layouts.app')

@section('title', 'Manage Properties - RM of Stanley')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap">
        <div>
            <h1><i class="bi bi-building me-2"></i>Manage Properties</h1>
            <p class="text-muted mb-0">Create, edit, and manage all properties</p>
        </div>
        <a href="{{ route('admin.properties.create') }}" class="btn btn-primary mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-2"></i> Add New Property
        </a>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>All Properties</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Capacity</th>
                                <th>Price/Hour</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($properties as $property)
                                <tr>
                                    <td>{{ $property->name }}</td>
                                    <td>
                                        <span class="badge" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
                                            {{ $property->type }}
                                        </span>
                                    </td>
                                    <td>{{ $property->location ?? 'N/A' }}</td>
                                    <td>{{ $property->capacity ?? 'N/A' }}</td>
                                    <td>
                                        @if($property->price_per_hour)
                                            ${{ number_format($property->price_per_hour, 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($property->is_active)
                                            <span class="status-badge status-available" style="padding: 0.375rem 0.75rem;">
                                                <i class="bi bi-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="badge" style="background: #6b7280; color: white; padding: 0.375rem 0.75rem;">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.properties.edit', $property) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this property? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        No properties found. <a href="{{ route('admin.properties.create') }}">Create your first property</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

