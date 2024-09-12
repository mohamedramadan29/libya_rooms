$(function(e) {
	//file export datatable
	var table = $('#example').DataTable({
		lengthChange: false,
        buttons: [
            {
                extend: 'copy',
                text: 'نسخ'
            },
            {
                extend: 'excel',
                text: 'استخراج ملف Excel'
            },
            {
                extend: 'pdf',
                text: 'استخراج ملف PDF'
            },
            {
                extend: 'colvis',
                text: 'إظهار/إخفاء الأعمدة'
            }
        ],
		responsive: false,
		language: {},
        order: [[0, 'desc']],
	});
	table.buttons().container()
	.appendTo( '#example_wrapper .col-md-6:eq(0)' );

	$('#example1').DataTable({
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_',
		}
	});
    $('#example2').DataTable({
        responsive: false,
        lengthChange: false,
        searching: true,
        language: {},
        order: [[0, 'desc']], // هذا يحدد الترتيب الافتراضي حسب العمود الأول بشكل تنازلي
        columnDefs: [
            { targets: [$('#column1').index(), $('#column2').index()], searchable: false }
        ]
    });



	var table = $('#example-delete').DataTable({
		responsive: true,
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_',
		}
	});
    $('#example-delete tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $('#button').click( function () {
        table.row('.selected').remove().draw( false );
    } );

	//Details display datatable
	$('#example-1').DataTable( {
		responsive: true,
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_',
		},
		responsive: {
			details: {
				display: $.fn.dataTable.Responsive.display.modal( {
					header: function ( row ) {
						var data = row.data();
						return 'Details for '+data[0]+' '+data[1];
					}
				} ),
				renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
					tableClass: 'table border mb-0'
				} )
			}
		}
	} );
});
