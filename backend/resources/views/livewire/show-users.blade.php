<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow p-6">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-blue-500">
            <tr>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">ID</th>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">Nombre</th>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">Email</th>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">Rol</th>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">Creado</th>
            </tr>
          </thead>
  
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($users as $user)
              <tr>
                <td class="px-6 py-7 whitespace-nowrap">{{ $user->id }}</td>
                <td class="px-6 py-7 whitespace-nowrap">{{ $user->name }}</td>
                <td class="px-6 py-7 whitespace-nowrap">{{ $user->email }}</td>
                <td class="px-6 py-7 whitespace-nowrap">{{ $user->role ?? 'Usuario' }}</td>
                <td class="px-6 py-7 whitespace-nowrap">{{ $user->created_at->format('d/m/Y') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  
      <div class="mt-4">
        {{ $users->onEachSide(1)->links() }}
      </div>
    </div>
  </div>
  