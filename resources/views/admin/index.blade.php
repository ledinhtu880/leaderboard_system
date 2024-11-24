@foreach ($groups as $group)
    <div class="card">
        <h3>{{ $group->name }}</h3>
        <table>
            <tr>
                <th>Member</th>
                <th>Compatibility Score</th>
                <th>Status</th>
            </tr>
            @foreach ($group->members as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->pivot->compatibility_score }}%</td>
                    <td>{{ $member->pivot->status }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endforeach
