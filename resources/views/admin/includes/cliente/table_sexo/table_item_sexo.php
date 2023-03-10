<tr>
    <td>
        #
    </td>
    <td>
        <a>
            {{nome_cliente}}
        </a>
    </td>
    <td>
        <a>
            {{idade_cliente}}
        </a>
    </td>
    <td>
        <a>
            {{carros_registrados}}
        </a>
    </td>
    <td>
        <a>
            {{media_tempo_revisao}}
        </a>
    </td>
    <td>
        <a>
            {{previsao_proxima_revisao}}
        </a>
    </td>
    <td class="project-actions text-right">
        <a class="btn btn-info btn-sm" href="{{URL}}/dashboard/clientes/{{id_cliente}}/carros">
            <i class="fas fa-car">
            </i>
            Ver carros
        </a>
        <a class="btn btn-danger btn-sm toastrDeletedSuccess" href="{{URL}}/dashboard/clientes/{{id_cliente}}/delete">
            <i class="fas fa-trash">
            </i>
            Deletar
        </a>
    </td>
</tr>