<div>


    <h3 style="margin: 1px;">{{ $title }}</h3>
    <div>
        @php
            $padding = 'padding: 5px';
            $stylecard = 'border: 1px solid #e0e4e6;margin-bottom: 16px;margin-top: 16px;';
        @endphp
        @foreach ($list as $num=>$item)

            @php
                $tuser+=$item->userScore->user_score;
                $tmanager+=$item->userScore->manager_score;
                $thr+=$item->userScore->hr_score;
                $c+=1;

            @endphp

            <div>
      <div style="font-weight: bold;font-size: 22px;font-style: italic;">
      </div>
                <table style="width: 100%;">
                    <tr>
                        <th style="background-color: {{ $bgParent }};{{ $padding }}">
                            KPI #{{ ($num + 1) }}
                        </th>
                        <th style="background-color: {{ $bgParent }};{{ $padding }}">
                            Percentage
                        </th>
                        <th style="background-color: {{ $bgParent }};{{ $padding }}">
                            Your Score
                        </th>
                        <th style="background-color: {{ $bgParent }};{{ $padding }}">
                            Manager Score
                        </th>
                        <th style="background-color: {{ $bgParent }};{{ $padding }}">
                            HR Score
                        </th>

                    </tr>
                    <tr>
                        <td  style="background-color: #eef2f5;{{ $padding }};text-align: center;">
                            {{ $item->requirement }}
                        </td>
                        <td style="background-color: #eef2f5;text-align: center;{{ $padding }};text-align: center;">
                            {{ $item->percentage }}%
                        </td>
                        <td style="background-color: #eef2f5;{{ $padding }};text-align: center;">
                            {{ $item->userScore->user_score }}%
                        </td>
                        <td style="background-color: #eef2f5;{{ $padding }};text-align: center;">
                            {{ $item->userScore->manager_score }}%
                        </td>
                        <td style="background-color: #eef2f5;{{ $padding }};text-align: center;">
                            {{ $item->userScore->hr_score }}%
                        </td>

                    </tr>
                </table>

                <h4 style="margin: 5px;font-weight: bold;font-size: 17px;"><u>Comments</u></h4>

                <table style="width: 100%;">
                    <tr>
                        <th style="background-color: {{ $bgChild }};{{ $padding }}">
                            Your Comment
                        </th>
                        <th style="background-color: {{ $bgChild }};{{ $padding }}">
                            Manager Comment
                        </th>
                        <th style="background-color: {{ $bgChild }};{{ $padding }}">
                            HR Comment
                        </th>
                    </tr>
                    <tr>
                        <td  style="background-color: #eef2f5;{{ $padding }}">
                            {{ $item->userScore->user_comment }}
                        </td>
                        <td style="background-color: #eef2f5;text-align: center;{{ $padding }}">
                            {{ $item->userScore->manager_comment }}
                        </td>
                        <td style="background-color: #eef2f5;{{ $padding }}">
                            {{ $item->userScore->hr_comment }}
                        </td>
                    </tr>
                </table>




            </div>

        @endforeach
    </div>


</div>
