<x-app-layout>
    <section class="retina-gradient rounded-[2rem] p-8 lg:p-10 text-white shadow-2xl overflow-hidden relative">
        <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute right-10 bottom-0 h-40 w-40 rounded-full bg-cyan-300/20 blur-3xl"></div>

        <div class="relative grid lg:grid-cols-2 gap-8 items-center">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-semibold ring-1 ring-white/20">
                        لوحة التحكم الطبية
                    </div>

                    <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-2 text-sm font-bold ring-1 ring-white/20" title="حالة اتصال FastAPI">
                        <span class="h-2.5 w-2.5 rounded-full {{ $fastApiOnline ? 'bg-emerald-300' : 'bg-rose-300' }}"></span>
                        <span class="text-blue-50">FastAPI</span>
                        <span>{{ $fastApiOnline ? 'متصل' : 'غير متصل' }}</span>
                    </div>
                </div>

                <h1 class="mt-6 text-4xl font-black">مرحبًا، {{ Auth::user()->name }}</h1>
                <p class="mt-4 text-blue-50 leading-8">
                    تابع ملفات المرضى ونتائج تحليل صور الشبكية والتقارير الطبية من مكان واحد بواجهة منظمة ومناسبة للاستخدام السريري.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <a href="{{ route('patients.create') }}" class="rounded-3xl bg-white/15 p-6 hover:bg-white/20 ring-1 ring-white/20 transition">
                    <div class="text-3xl">➕</div>
                    <div class="mt-3 font-black text-xl">إضافة مريض</div>
                    <p class="text-sm text-blue-100 mt-1">إنشاء ملف طبي جديد</p>
                </a>

                <a href="{{ route('diagnoses.create') }}" class="rounded-3xl bg-white text-blue-700 p-6 hover:bg-blue-50 shadow-xl transition">
                    <div class="text-3xl">👁️</div>
                    <div class="mt-3 font-black text-xl">تشخيص جديد</div>
                    <p class="text-sm text-blue-600 mt-1">رفع صورة شبكية</p>
                </a>
            </div>
        </div>
    </section>

    <section class="mt-8 grid sm:grid-cols-2 xl:grid-cols-5 gap-5">
        <div class="glass-card rounded-3xl p-6">
            <div class="text-slate-500 text-sm">عدد المرضى</div>
            <div class="mt-3 text-4xl font-black text-slate-900">{{ $patientsCount }}</div>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="text-slate-500 text-sm">إجمالي التشخيصات</div>
            <div class="mt-3 text-4xl font-black text-blue-700">{{ $diagnosesCount }}</div>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="text-slate-500 text-sm">تشخيصات اليوم</div>
            <div class="mt-3 text-4xl font-black text-cyan-700">{{ $todayDiagnoses }}</div>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="text-slate-500 text-sm">حالات عالية الخطورة</div>
            <div class="mt-3 text-4xl font-black text-rose-600">{{ $highRiskCount }}</div>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="text-slate-500 text-sm">متوسط الثقة</div>
            <div class="mt-3 text-4xl font-black text-emerald-600">
                {{ $avgConfidence ? round($avgConfidence * 100) . '%' : '0%' }}
            </div>
        </div>
    </section>

    <section class="mt-8 grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-card rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black">آخر التشخيصات</h2>
                <a href="{{ route('diagnoses.index') }}" class="text-sm font-bold text-blue-700 hover:text-blue-900">
                    عرض الكل
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="text-slate-500 border-b">
                        <th class="text-right py-3">المريض</th>
                        <th class="text-right py-3">النتيجة</th>
                        <th class="text-right py-3">الثقة</th>
                        <th class="text-right py-3">الخطورة</th>
                        <th class="text-right py-3"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse ($latestDiagnoses as $diagnosis)
                        <tr>
                            <td class="py-4 font-bold">{{ $diagnosis->patient?->full_name }}</td>
                            <td class="py-4">{{ $diagnosis->predicted_class }}</td>
                            <td class="py-4">{{ $diagnosis->confidence_percent }}%</td>
                            <td class="py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $diagnosis->risk_level === 'high' ? 'bg-rose-100 text-rose-700' : ($diagnosis->risk_level === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    {{ $diagnosis->risk_label }}
                                </span>
                            </td>
                            <td class="py-4">
                                <a href="{{ route('diagnoses.show', $diagnosis) }}" class="font-bold text-blue-700 hover:text-blue-900">
                                    التقرير
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">لا توجد تشخيصات بعد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass-card rounded-3xl p-6">
                <h2 class="text-xl font-black mb-4">توزيع النتائج</h2>
                <div class="space-y-4">
                    @forelse ($diseaseDistribution as $item)
                        <div>
                            <div class="flex justify-between text-sm font-bold">
                                <span>{{ $item->predicted_class }}</span>
                                <span>{{ $item->total }}</span>
                            </div>
                            <div class="mt-2 h-3 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-3 rounded-full bg-blue-700" style="width: {{ min(100, $diagnosesCount ? ($item->total / $diagnosesCount) * 100 : 0) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500">لا توجد بيانات كافية.</p>
                    @endforelse
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6">
                <h2 class="text-xl font-black mb-4">مؤشرات المراجعة الطبية</h2>

                <div class="space-y-3">
                    <div class="flex items-center justify-between rounded-2xl bg-emerald-50 px-4 py-3">
                        <span class="font-bold text-emerald-800">منخفضة الخطورة</span>
                        <span class="font-black text-emerald-700">{{ $lowRiskCount }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-amber-50 px-4 py-3">
                        <span class="font-bold text-amber-800">متوسطة الخطورة</span>
                        <span class="font-black text-amber-700">{{ $mediumRiskCount }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-rose-50 px-4 py-3">
                        <span class="font-bold text-rose-800">عالية الخطورة</span>
                        <span class="font-black text-rose-700">{{ $highRiskCount }}</span>
                    </div>
                </div>

                <p class="mt-4 text-sm text-slate-500 leading-7">
                    تساعد هذه المؤشرات الطبيب على ترتيب الحالات التي تحتاج إلى مراجعة أسرع.
                </p>
            </div>
        </div>
    </section>
</x-app-layout>
