<?php

namespace Tests\Feature\Expense;

use App\Models\Expense;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use DatabaseMigrations;

    public function testRequestCanCreateAnExpense()
    {
        // Prepare
        $payload = [
            'descricao' => 'Mercado',
            'valor' => 800,
            'data' => '08-08-2022',
            'categoria_id' => 1
        ];

        // Act
        $response = $this->postJson('api/despesas', $payload, [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertCreated()
                 ->assertJson([
                    'id' => 1,
                    'descricao' => 'Mercado',
                    'data' => '2022-08-08',
                    'categoria' => 'Alimentação'
                 ]);
    }

    public function testRequestCanNotCreateAExpenseWithADuplicateDescriptionForTheSameMonth()
    {
        // Prepare
        $payloadExpense1 = [
            'descricao' => 'Alimentação',
            'valor' => 800,
            'data' => '2022-08-05',
            'categoria_id' => 1
        ];
        $payloadExpense2 = [
            'descricao' => 'Alimentação',
            'valor' => 700,
            'data' => '2022-08-10',
            'categoria_id' => 1
        ];

        // Act
        $this->postJson('api/despesas', $payloadExpense1, [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);
        $responseExpense2 = $this->postJson('api/despesas', $payloadExpense2, [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $responseExpense2->assertStatus(409)
                 ->assertJson(['message' => "A despesa '{$payloadExpense2['descricao']}' já foi informada para este mês"]);
    }

    public function testRequestCanRetrieveASpecificExpense()
    {
        // Prepare
        $expense = Expense::factory()->create();

        // Act
        $uri = "api/despesas/{$expense->id}";
        $response = $this->getJson($uri, [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertOk()
                 ->assertJson([
                    'descricao' => $expense->descricao,
                    'valor' => $expense->valor,
                    'data' => $expense->data,
                    'categoria' => $expense->categoria->nome
                 ]);
    }

    public function testRequestCanUpdateAnExpense()
    {
        // Prepare
        $expense = Expense::factory()->create();

        // Act
        $uri = "api/despesas/{$expense->id}";
        $newDescription = 'Job';
        $response = $this->putJson($uri,
        [
            'descricao' => $newDescription,
            'valor' => $expense->valor,
            'data' => $expense->data,
            'categoria_id' => $expense->categoria_id
        ],
        [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertOk()
                 ->assertJson(['descricao' => $newDescription]);
    }

    public function testRequestCanNotUpdateAExpenseWithADuplicateDescriptionForTheSameMonth()
    {
        // Prepare
        $expense1 = Expense::factory()->create();
        $expense2 = Expense::factory()->create();

        // Act
        $uriExpense2 = "api/despesas/{$expense2->id}";
        $response = $this->putJson($uriExpense2,
        [
            'descricao' => $expense1->descricao,
            'valor' => $expense2->valor,
            'data' => $expense1->data,
            'categoria_id' => $expense1->categoria_id
        ],
        [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertStatus(409)
                 ->assertJson(['message' => "A despesa '{$expense1->descricao}' já foi informada para o mês"]);
    }

    public function testRequestCanDeleteAExpense()
    {
        // Prepare
        $expense = Expense::factory()->create();

        // Act
        $uri = "api/despesas/{$expense->id}";
        $response = $this->deleteJson($uri, [], [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertNoContent()
                 ->assertDontSee($expense);
    }
}
