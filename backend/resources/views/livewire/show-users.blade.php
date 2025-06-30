<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Usuarios</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-300">
                    <tr>
                        <th class="px-6 py-7 text-left text-sm font-medium text-black-500 uppercase tracking-wider leading-normal">ID</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-black-500 uppercase tracking-wider leading-normal">Nombre</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-black-500 uppercase tracking-wider leading-normal">Email</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-black-500 uppercase tracking-wider leading-normal">Rol</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-black-500 uppercase tracking-wider leading-normal">Creado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-7 whitespace-nowrap">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->role ?? 'Usuario' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
