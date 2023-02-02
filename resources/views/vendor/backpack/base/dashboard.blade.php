@extends(backpack_view('blank'))

@php
    $start  = \Illuminate\Support\Carbon::now()->startOfWeek();
    $end    = \Illuminate\Support\Carbon::now()->endOf('week');
    $orders = \App\Models\UserOrder::query()
    ->whereBetween('finished_at', [$start, $end])
    ->whereIn('status', [\App\Helpers\Status::FINISHED, \App\Helpers\Status::CANCELED]);
    $bestAssembler = (clone $orders)->selectRaw('user_id, count(user_id) as count')->groupBy('user_id')->orderBy('count', 'desc')->first();
    $bestAssembler = $bestAssembler?->user()->first();
    $orders = $orders->get();

    $widgets['before_content'][] = [
        'type'    => 'div',
        'class'   => 'row',
        'content' =>
            [
                [
                    'type' => 'card',
                    'class' => 'card bg-light text-center',
                    'content' => [
                            'header'    => '<b>Всего сборок за неделю:</b>',
                            'body'      => $orders->count(),
                        ]
                ],
                [
                    'type' => 'card',
                    'class' => 'card bg-light text-center',
                    'content' => [
                            'header'    => '<b>Количество завершенных сборок</b>',
                            'body'      => $orders->where('status', \App\Helpers\Status::FINISHED)->count(),
                        ]
                ],
                [
                    'type' => 'card',
                    'class' => 'card bg-light text-center',
                    'content' => [
                            'header'    => '<b>Лучший сборщик</b>',
                            'body'      => $bestAssembler->full_name ?? 'Ни одного заказа не было собрано в эту неделю',
                        ]
                ],
            ]
    ];
    $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => trans('backpack::base.welcome'),
        'content'     => trans('backpack::base.use_sidebar'),
        'button_link' => backpack_url('logout'),
        'button_text' => trans('backpack::base.logout'),
    ];

@endphp

@section('content')
@endsection
