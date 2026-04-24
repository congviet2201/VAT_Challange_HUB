<?php

namespace Tests\Unit;

use App\Services\GoalAIService;
use ReflectionMethod;
use Tests\TestCase;

class GoalAIServiceTest extends TestCase
{
    public function test_parse_sub_goals_accepts_valid_json_array(): void
    {
        $service = new GoalAIService();

        $result = $this->invokeParseSubGoals(
            $service,
            '[{"title":"Buoc 1","description":"Lam viec nho gon.","day":1},{"title":"Buoc 2","description":"Tiep tuc tien do.","day":99},{"title":"Buoc 3","description":"Tong ket ngay.","day":-5}]',
            3
        );

        $this->assertSame([
            [
                'title' => 'Buoc 1',
                'description' => "- Action: Lam viec nho gon.\n- Metric: Hoan thanh day du muc tieu cua moc nay.\n- Expected result: Tao tien do ro rang cho muc tieu chinh.",
                'day' => 1,
            ],
            [
                'title' => 'Buoc 2',
                'description' => "- Action: Tiep tuc tien do.\n- Metric: Hoan thanh day du muc tieu cua moc nay.\n- Expected result: Tao tien do ro rang cho muc tieu chinh.",
                'day' => 2,
            ],
            [
                'title' => 'Buoc 3',
                'description' => "- Action: Tong ket ngay.\n- Metric: Hoan thanh day du muc tieu cua moc nay.\n- Expected result: Tao tien do ro rang cho muc tieu chinh.",
                'day' => 3,
            ],
        ], $result);
    }

    public function test_parse_sub_goals_salvages_complete_items_from_truncated_json(): void
    {
        $service = new GoalAIService();

        $result = $this->invokeParseSubGoals(
            $service,
            '[{"title":"Kiem soat bua an sang","description":"Chi an yen mach va trung luoc.","day":1},{"title":"Tang cuong van dong","description":"Cardio lien tuc it nhat 30 phut.","day":4},{"title":"Uong du nuoc","description":"Dat muc tieu 2 lit moi ngay.","day":',
            2
        );

        $this->assertSame([
            [
                'title' => 'Kiem soat bua an sang',
                'description' => "- Action: Chi an yen mach va trung luoc.\n- Metric: Hoan thanh day du muc tieu cua moc nay.\n- Expected result: Tao tien do ro rang cho muc tieu chinh.",
                'day' => 1,
            ],
            [
                'title' => 'Tang cuong van dong',
                'description' => "- Action: Cardio lien tuc it nhat 30 phut.\n- Metric: Hoan thanh day du muc tieu cua moc nay.\n- Expected result: Tao tien do ro rang cho muc tieu chinh.",
                'day' => 2,
            ],
        ], $result);
    }

    public function test_parse_sub_goals_infers_missing_day_and_normalizes_star_bullets(): void
    {
        $service = new GoalAIService();

        $result = $this->invokeParseSubGoals(
            $service,
            '[{"title":"Tang cuong dot chay calo toan than","description":"* Action: Thuc hien buoi tap HIIT 45 phut.\n* Metric: Hoan thanh it nhat 12 hiep bai tap.\n* Expected result: Dot chay toi da luong calo du thua."}]',
            1
        );

        $this->assertSame([
            [
                'title' => 'Tang cuong dot chay calo toan than',
                'description' => "- Action: Thuc hien buoi tap HIIT 45 phut.\n- Metric: Hoan thanh it nhat 12 hiep bai tap.\n- Expected result: Dot chay toi da luong calo du thua.",
                'day' => 1,
            ],
        ], $result);
    }

    public function test_parse_sub_goals_rejects_count_mismatch_with_duration_days(): void
    {
        $service = new GoalAIService();

        $result = $this->invokeParseSubGoals(
            $service,
            '[{"title":"Ngay 1","description":"Lam viec 1","day":1}]',
            3
        );

        $this->assertNull($result);
    }

    private function invokeParseSubGoals(GoalAIService $service, string $rawContent, int $durationDays): ?array
    {
        $method = new ReflectionMethod($service, 'parseSubGoals');
        $method->setAccessible(true);

        return $method->invoke($service, $rawContent, $durationDays);
    }
}
